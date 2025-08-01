<?php

namespace App\Http\Controllers;

use App\Models\MwPricePlanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\MwPackageNew;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\MwUserPackage;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function subscriptionCheckout(Request $request)
    {
        $request->validate([
            'package_id' => 'required|integer|exists:mysql_legacy.mw_package_new,package_id',
        ]);

        $package = MwPackageNew::where('package_id', $request->package_id)->first();
        
        if (!$package->stripe_price_id) {
            return response()->json(['error' => 'Package not found in Stripe'], 404);
        }

        $subscription = $request->user()->newSubscription('default', $package->stripe_price_id);
    
        $checkoutSession = $subscription
            ->allowPromotionCodes()
            ->checkout([
                'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}&package_id=' . $package->package_id,
                'cancel_url' => route('subscription.cancel'),
                'automatic_tax' => ['enabled' => false],
                'metadata' => [
                    'package_id' => $package->package_id
                ],
                'subscription_data' => [
                    'default_tax_rates' => ['txr_1Rl5NADESgxMjdSSpudtcozg']
                ],
            ]);

        return response()->json([
            'checkout_url' => $checkoutSession->url,
            'session_id' => $checkoutSession->id,
        ]);
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $packageId = $request->get('package_id');
        
        if (!$sessionId) {
            return response()->json(['error' => 'Invalid session'], 400);
        }

        try {
            $session = Session::retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                // Handle successful payment
                $order = $this->handleSuccessfulPayment($session,$packageId);
                return response()->json([
                    'message' => 'Payment successful',
                    'order' => $order,
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment verification failed'], 400);
        }

        return response()->json(['error' => 'Payment verification failed'], 400);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        return response()->json(['message' => 'Payment was cancelled'], 200);
    }

    /**
     * Handle subscription success
     */
    public function subscriptionSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return response()->json(['error' => 'Invalid session'], 400);
        }

        return response()->json(['message' => 'Subscription created successfully'], 200);
    }

    /**
     * Handle subscription cancellation
     */
    public function subscriptionCancel()
    {
        return response()->json(['message' => 'Subscription setup was cancelled'], 200);
    }

    /**
     * Create a customer portal session
     */
    public function customerPortal(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasStripeId()) {
            return response()->json(['error' => 'No Stripe customer found'], 400);
        }

        $session = $user->billingPortalSession(route('home'));

        return response()->json([
            'portal_url' => $session->url,
        ]);
    }

    /**
     * Get user's subscription status
     */
    public function subscriptionStatus(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $subscription = $user->subscription('default');
        
        if (!$subscription) {
            return response()->json(['status' => 'inactive']);
        }

        return response()->json([
            'status' => $subscription->stripe_status,
            'current_period_end' => $subscription->ends_at,
            'trial_ends_at' => $subscription->trial_ends_at,
            'on_trial' => $subscription->onTrial(),
            'cancelled' => $subscription->cancelled(),
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription('default');

        if (!$subscription) {
            return response()->json(['error' => 'No active subscription found'], 404);
        }

        $subscription->cancel();

        return response()->json(['message' => 'Subscription cancelled successfully']);
    }

    /**
     * Resume subscription
     */
    public function resumeSubscription(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription('default');

        if (!$subscription) {
            return response()->json(['error' => 'No subscription found'], 404);
        }

        $subscription->resume();

        return response()->json(['message' => 'Subscription resumed successfully']);
    }

    /**
     * Handle Stripe webhooks
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
            
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;
            
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;
            
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful payment processing
     */
    private function handleSuccessfulPayment($session, $packageId = null)
    {
       
        $customerEmail = $session->customer_details->email ?? null;
        $user = User::where('email', $customerEmail)->first();
        $userId = $user ? $user->user_id : null;
       
        $order = new MwPricePlanOrder();
        $order->order_uid = $this->generateUid();
        $order->customer_id = $userId;
        $order->plan_id = 0;
        $order->feature_id = $packageId;
        $order->currency_id = 1;
        $order->subtotal = $session->amount_subtotal / 100;
        $order->tax_percent = 0;
        $order->tax_value = ($session->total_details->amount_tax ?? 0) / 100;
        $order->total = $session->amount_total / 100;
        $order->status = 'complete';
        $order->date_added = now();
        $order->last_updated = now();
        $order->payment_type = 't';

        $order->save();

        return $order;
    }

    /**
     * Handle checkout session completed
     */
    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout session completed', ['session_id' => $session->id]);
        
        if ($session->mode === 'payment') {
            $order = $this->handleSuccessfulPayment($session);
            Log::info('Payment order created', ['order_id' => $order->order_id]);
        }
        
        if ($session->mode === 'subscription') {
            // Get customer details from the session
            $customerEmail = $session->customer_details->email ?? null;
            $user = User::where('email', $customerEmail)->first();
            $userId = $user ? $user->user_id : null;

            // Create an order for subscription payment
            $order = new MwPricePlanOrder();
            $order->order_uid = $this->generateUid();
            $order->customer_id = $userId;
            $order->plan_id = 0;
            $order->feature_id = $session->metadata->package_id ?? null;
            $order->currency_id = 1;
            $order->subtotal = $session->amount_subtotal / 100;
            $order->tax_percent = 0;
            $order->tax_value = ($session->total_details->amount_tax ?? 0) / 100; 
            $order->total = $session->amount_total / 100; 
            $order->status = 'complete';
            $order->date_added = now();
            $order->last_updated = now();
            $order->payment_type = 't';
            $order->save();

            $this->createUserPackage($order);

            Log::info('Subscription order created', ['order_id' => $order->order_id]);
        }
    }

    /**
     * Handle subscription created
     */
    private function handleSubscriptionCreated($subscription)
    {
        try {
            Log::info('Processing subscription creation', ['subscription_id' => $subscription->id]);
            
            $user = User::on('mysql_legacy')
                ->where('stripe_id', $subscription->customer)
                ->firstOrFail();

            // Update user's subscription status
            $user->update([
                'subscription_status' => 'active',
                'subscription_id' => $subscription->id,
                'subscription_end_date' => Carbon::createFromTimestamp($subscription->current_period_end),
                'plan_id' => $subscription->metadata->package_id ?? null
            ]);

            // Create subscription record
            $subscriptionRecord = new Subscription();
            $subscriptionRecord->user_id = $user->user_id;
            $subscriptionRecord->stripe_id = $subscription->id;
            $subscriptionRecord->stripe_status = $subscription->status;
            $subscriptionRecord->stripe_price = $subscription->items->data[0]->price->id;
            $subscriptionRecord->quantity = $subscription->items->data[0]->quantity ?? 1;
            $subscriptionRecord->trial_ends_at = $subscription->trial_end ? Carbon::createFromTimestamp($subscription->trial_end) : null;
            $subscriptionRecord->ends_at = $subscription->cancel_at ? Carbon::createFromTimestamp($subscription->cancel_at) : null;
            $subscriptionRecord->type = 'default'; // Adding the required type field
            $subscriptionRecord->save();

            Log::info('Subscription created successfully', [
                'user_id' => $user->user_id,
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process subscription creation', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription updated
     */
    private function handleSubscriptionUpdated($subscription)
    {
        try {
            Log::info('Processing subscription update', ['subscription_id' => $subscription->id]);
            
            $user = User::on('mysql_legacy')
                ->where('stripe_id', $subscription->customer)
                ->firstOrFail();

            // Update subscription record
            $subscriptionRecord = Subscription::where('stripe_id', $subscription->id)->first();
            
            if ($subscriptionRecord) {
                $subscriptionRecord->stripe_status = $subscription->status;
                $subscriptionRecord->stripe_price = $subscription->items->data[0]->price->id;
                $subscriptionRecord->quantity = $subscription->items->data[0]->quantity ?? 1;
                $subscriptionRecord->trial_ends_at = $subscription->trial_end ? Carbon::createFromTimestamp($subscription->trial_end) : null;
                $subscriptionRecord->ends_at = $subscription->cancel_at ? Carbon::createFromTimestamp($subscription->cancel_at) : null;
                $subscriptionRecord->save();
            }

            // Update user's subscription details
            $user->update([
                'subscription_status' => $subscription->status,
                'subscription_end_date' => Carbon::createFromTimestamp($subscription->current_period_end),
                'plan_id' => $subscription->metadata->package_id ?? $user->plan_id
            ]);

            Log::info('Subscription updated successfully', [
                'user_id' => $user->user_id,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process subscription update', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
            throw $e;
        }
    }

    /**
     * Handle subscription deleted/cancelled
     */
    private function handleSubscriptionDeleted($subscription)
    {
        try {
            Log::info('Processing subscription deletion', ['subscription_id' => $subscription->id]);
            
            $user = User::on('mysql_legacy')
                ->where('stripe_id', $subscription->customer)
                ->firstOrFail();

            // Update user's subscription status
            $user->update([
                'subscription_status' => 'cancelled',
                'subscription_end_date' => Carbon::createFromTimestamp($subscription->current_period_end),
            ]);

            // Update subscription record
            $subscriptionRecord = Subscription::where('stripe_id', $subscription->id)->first();
            if ($subscriptionRecord) {
                $subscriptionRecord->stripe_status = 'cancelled';
                $subscriptionRecord->ends_at = Carbon::createFromTimestamp($subscription->current_period_end);
                $subscriptionRecord->save();
            }

            Log::info('Subscription cancelled successfully', [
                'user_id' => $user->user_id,
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process subscription deletion', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
            throw $e;
        }
    }

    /**
     * Handle successful invoice payment
     */
    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', ['invoice_id' => $invoice->id]);
        
        // Handle successful recurring payment
    }

    /**
     * Handle failed invoice payment
     */
    private function handleInvoicePaymentFailed($invoice)
    {
        Log::info('Invoice payment failed', ['invoice_id' => $invoice->id]);
        
        // Handle failed payment (notify user, etc.)
    }

    /**
     * Get available packages/products
     */
    public function getPackages()
    {
        $packages = MwPackageNew::where('active', true)->get();
        
        return response()->json([
            'packages' => $packages,
        ]);
    }

    /**
     * Get Stripe products for subscriptions
     */
    public function getSubscriptionProducts()
    {
        try {
            $products = \Stripe\Product::all(['limit' => 100]);
            $prices = \Stripe\Price::all(['limit' => 100]);
            
            return response()->json([
                'products' => $products->data,
                'prices' => $prices->data,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Stripe products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    private function generateUid(): string
    {
        $unique = date('ydm-His') . '-' . rand(10, 100);

        $exists = MwPricePlanOrder::where('order_uid', $unique)->exists();
        
        if ($exists) {
            return $this->generateUid();
        }
        
        return (string) $unique;
    }

    /**
     * Create user package from order details
     */
    public function createUserPackage($order)
    {
        if (!empty($order->feature_id)) {
            // Get package details
            $package = MwPackageNew::find($order->feature_id);
            if (!$package) {
                Log::error('Package not found', ['feature_id' => $order->feature_id]);
                return;
            }

            $category_id = $package->category;
            $max_listing_per_day = $package->max_listing_per_day;
            $validity_in_days = $package->validity_in_days;
            
            // Handle parent package if exists
            $parentpackage = null;
            $ar_package = [];
            if (!empty($package->parent_id)) {
                $parentpackage = MwPackageNew::find($package->parent_id);
                if ($parentpackage) {
                    $category_id = $parentpackage->category;
                    $max_listing_per_day = $parentpackage->max_listing_per_day;
                    $ar_package['features'] = array_filter($parentpackage->getAttributes());
                }
            }

            // Store package details in json
            if (empty($order->json_data)) {
                $ar_package['validity'] = array_filter($package->getAttributes());
                MwPricePlanOrder::where('order_id', $order->order_id)
                    ->update(['json_data' => json_encode($ar_package)]);
            }

            // Find existing or create new MwUserPackage record
            $orderChildModel = MwUserPackage::where('order_id', $order->order_id)->first();

            if (!$orderChildModel && $order->status == 'complete') {
                $orderChildModel = new MwUserPackage();
            }

            // Handle package extension for category 1
            $existing_package = null;
            if ($parentpackage->category == '1') {
                // Get active package for extension - find any active package with remaining time
                $existing_package = MwUserPackage::select([
                        'mw_user_packages.*',
                        DB::raw('DATEDIFF(DATE_ADD(date_added, INTERVAL validity DAY), CURDATE()) AS remaining_days'),
                        DB::raw('DATE_ADD(date_added, INTERVAL validity DAY) AS end_date'),
                        // Calculate remaining ads for different types
                        DB::raw('GREATEST(0, mw_user_packages.ads_allowed - COALESCE(mw_user_packages.listing_used, 0)) as regular_ads_remaining'),
                        DB::raw('GREATEST(0, mw_user_packages.number_of_hot_ads - COALESCE(mw_user_packages.hot_used, 0)) as hot_ads_remaining'),
                        DB::raw('GREATEST(0, mw_user_packages.number_of_featured_ads - COALESCE(mw_user_packages.featured_used, 0)) as featured_ads_remaining'),
                        DB::raw('GREATEST(0, mw_user_packages.daily_refresh_limit - COALESCE(mw_user_packages.refresh_used, 0)) as refresh_remaining'),
                        // Usage counts
                        DB::raw('COALESCE(mw_user_packages.listing_used, 0) as listing_used'),
                        DB::raw('COALESCE(mw_user_packages.hot_used, 0) as hot_used'),
                        DB::raw('COALESCE(mw_user_packages.featured_used, 0) as featured_used'),
                        DB::raw('COALESCE(mw_user_packages.refresh_used, 0) as refresh_used')
                    ])
                    ->where('status', 'active')
                    ->where('user_id', $order->customer_id)
                    ->where('latest', '1')
                    ->where('category_id', '1')
                    ->where(function($query) {
                        $query->whereNull('deleted_status')
                              ->orWhere('deleted_status', '0');
                    })
                    // Check if package is still valid (not expired)
                    ->where(function($query) {
                        $query->whereRaw('(CASE 
                            WHEN mw_user_packages.validity = "0" THEN 1
                            WHEN DATEDIFF(NOW(), mw_user_packages.date_added) < mw_user_packages.validity THEN 1
                            ELSE 0 
                        END) = 1');
                    })
                    // Check if package has ANY remaining resources OR remaining time
                    ->where(function($query) {
                        $query->whereRaw('(
                            mw_user_packages.ads_allowed > COALESCE(mw_user_packages.listing_used, 0) OR
                            mw_user_packages.number_of_hot_ads > COALESCE(mw_user_packages.hot_used, 0) OR
                            mw_user_packages.number_of_featured_ads > COALESCE(mw_user_packages.featured_used, 0) OR
                            mw_user_packages.daily_refresh_limit > COALESCE(mw_user_packages.refresh_used, 0) OR
                            DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) > 0
                        )');
                    })
                    ->first();

                // Add remaining days and transfer remaining ads if package exists
                if ($existing_package && $existing_package->remaining_days > 0) {
                    // Extend validity with remaining days
                    $validity_in_days += $existing_package->remaining_days;
                    
                    // Transfer remaining ads to new package
                    if ($existing_package->regular_ads_remaining > 0) {
                        $max_listing_per_day += $existing_package->regular_ads_remaining;
                        Log::info('Added remaining regular ads to new package', [
                            'remaining_ads' => $existing_package->regular_ads_remaining,
                            'new_total' => $max_listing_per_day
                        ]);
                    }
                    
                    Log::info('Extended package validity with existing package', [
                        'existing_remaining_days' => $existing_package->remaining_days,
                        'new_validity_days' => $validity_in_days,
                        'regular_ads_transferred' => $existing_package->regular_ads_remaining,
                        'hot_ads_remaining' => $existing_package->hot_ads_remaining,
                        'featured_ads_remaining' => $existing_package->featured_ads_remaining,
                        'refresh_remaining' => $existing_package->refresh_remaining
                    ]);
                    
                    // Mark existing package as inactive since we're creating a new one
                    MwUserPackage::where('id', $existing_package->id)
                        ->update([
                            'status' => 'inactive',
                            'latest' => '0'
                        ]);
                }
            }

            // Save package details
            $order1 = $orderChildModel;
            $order1->package_id = $package->package_id;
            $order1->ads_allowed = $max_listing_per_day;
            $order1->validity = $validity_in_days;
            $order1->amount = 0;
            $order1->used_ad = 0;
            $order1->category_id = $category_id;
            $order1->order_id = $order->order_id;
            $order1->user_id = $order->customer_id;
                
                // Copy fields from parent package
                $packageFields = $this->getPackageFields();
                if ($parentpackage) {
                    Log::info('Parent package found, copying fields', [
                        'parent_package_id' => $parentpackage->package_id,
                        'fields_to_copy' => $packageFields
                    ]);
                    
                    foreach ($packageFields as $field) {
                        try {
                            $value = $parentpackage->$field;
                            $order1->$field = $value;
                            Log::info('Copied field from parent', [
                                'field' => $field,
                                'value' => $value
                            ]);
                        } catch (\Exception $e) {
                            Log::warning('Could not copy field from parent package', [
                                'field' => $field,
                                'error' => $e->getMessage(),
                                'parent_package_id' => $parentpackage->package_id
                            ]);
                        }
                    }
                } else {
                    Log::info('No parent package to copy fields from');
                }

                // Transfer remaining resources from existing package if applicable
                if ($existing_package && $existing_package->remaining_days > 0) {
                    // Add remaining hot ads
                    if ($existing_package->hot_ads_remaining > 0) {
                        $order1->number_of_hot_ads = ($order1->number_of_hot_ads ?? 0) + $existing_package->hot_ads_remaining;
                        Log::info('Added remaining hot ads to new package', [
                            'remaining_hot_ads' => $existing_package->hot_ads_remaining,
                            'new_total' => $order1->number_of_hot_ads
                        ]);
                    }
                    
                    // Add remaining featured ads
                    if ($existing_package->featured_ads_remaining > 0) {
                        $order1->number_of_featured_ads = ($order1->number_of_featured_ads ?? 0) + $existing_package->featured_ads_remaining;
                        Log::info('Added remaining featured ads to new package', [
                            'remaining_featured_ads' => $existing_package->featured_ads_remaining,
                            'new_total' => $order1->number_of_featured_ads
                        ]);
                    }
                    
                    // Add remaining refresh limits
                    if ($existing_package->refresh_remaining > 0) {
                        $order1->daily_refresh_limit = ($order1->daily_refresh_limit ?? 0) + $existing_package->refresh_remaining;
                        Log::info('Added remaining refresh limits to new package', [
                            'remaining_refresh' => $existing_package->refresh_remaining,
                            'new_total' => $order1->daily_refresh_limit
                        ]);
                    }
                    
                    Log::info('Transferred all remaining resources from existing package', [
                        'regular_ads_transferred' => $existing_package->regular_ads_remaining,
                        'hot_ads_transferred' => $existing_package->hot_ads_remaining,
                        'featured_ads_transferred' => $existing_package->featured_ads_remaining,
                        'refresh_transferred' => $existing_package->refresh_remaining
                    ]);
                }
                
                $order1->parent_package = null;
                if (!$order1->exists) {
                    $order1->latest = '1';
                    $order1->status = 'active';
                    $order1->date_added = now();
                }
                
                $order1->save();
                
                Log::info('User package created successfully', [
                    'order_id' => $order->order_id,
                    'user_id' => $order->customer_id,
                    'package_id' => $package->package_id
                ]);
        }
    }

    /**
     * Get package fields that should be copied from parent package
     */
    private function getPackageFields(): array
    {
        return [
            'number_of_spnsored_ad',
            'number_of_agents',
            'max_listing_per_day',
            'number_of_images',
            'number_of_featured_ads',
            'number_of_featured_ads_days',
            'number_of_hot_ads',
            'number_of_hot_ads_days',
            'daily_refresh_limit',
            'analytics',
            'whatsapp',
            'email'
        ];
    }
}
