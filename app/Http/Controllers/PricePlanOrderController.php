<?php

namespace App\Http\Controllers;

use App\Models\MwPricePlanOrder;
use App\Models\MwUserPackage;
use App\Models\MwOption;
use App\Http\Resources\PricePlanOrderListResource;
use App\Http\Resources\PricePlanOrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class PricePlanOrderController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::user()->user_id;
        
        $query = MwPricePlanOrder::query()
            ->where('customer_id', $user_id)
            ->with(['mw_currency', 'mw_package_new', 'mw_listing_user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('date_added', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('date_added', '<=', $request->date_to);
        }
        
        $sortField = $request->get('sort_by', 'date_added');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => PricePlanOrderListResource::collection($orders),
                'pagination' => [
                    'total' => $orders->total(),
                    'per_page' => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                ]
            ]
        ]);
    }

    public function show($orderId)
    {
        try {
            $user_id = Auth::user()->user_id;
            
            $order = MwPricePlanOrder::query()
                ->where('order_id', $orderId)
                ->where('customer_id', $user_id)
                ->with([
                    'mw_currency',
                    'mw_tax',
                    'mw_package_new',
                    'mw_listing_user',
                    'mw_price_plan_promo_code',
                    'mw_place_an_ad',
                    'mw_user_packages'
                ])
                ->first();
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found or access denied'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => new PricePlanOrderResource($order)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function manage_subscription()
	{
        try {
            $userId = Auth::user()->user_id;
            $activePackage = MwUserPackage::findActivePackages($userId);

            $latestOrders = MwPricePlanOrder::with(['mw_package_new', 'mw_currency'])
                ->where('customer_id', $userId)
                ->orderBy('date_added', 'desc')
                ->limit(2)
                ->get();

            $subscription = null;
            if ($activePackage) {
                $subscription = [
                    'package_name' => $activePackage->package_name ?? 'Unknown Plan',
                    'validity' => $activePackage->ValidityNewTile ?? 'No validity information',
                    'quotas' => [
                        'listings' => $activePackage->ads_allowed ?? 0,
                        'used_listings' => $activePackage->used_ad ?? 0,
                        'featured_ads' => $activePackage->number_of_featured_ads ?? 0,
                        'featured_used' => $activePackage->featured_used ?? 0,
                        'hot_ads' => $activePackage->number_of_hot_ads ?? 0,
                        'hot_used' => $activePackage->hot_used ?? 0,
                        'daily_refresh' => $activePackage->daily_refresh_limit ?? 0,
                        'refresh_used' => $activePackage->refresh_used ?? 0,
                        'agents_allowed' => $activePackage->number_of_agents ?? 0,
                        'agents_used' => $activePackage->agents_used ?? 0
                    ],
                    'package_details' => [
                        'amount_paid' => $activePackage->total ?? 0,
                        'validity_days' => $activePackage->validity ?? 0,
                        'remaining_days' => $activePackage->remaining_days ?? 0,
                        'date_added' => $activePackage->date_added ? $activePackage->date_added->format('Y-m-d H:i:s') : null,
                        'end_date' => $activePackage->end_date ?? null
                    ]
                ];
            }

            return [
                'subscription' => $subscription,
                'orders' => PricePlanOrderListResource::collection($latestOrders),
            ];
        } catch (\Exception $e) {
            return [
                'subscription' => null,
                'orders' => [],
                'error' => 'Failed to retrieve subscription details: ' . $e->getMessage()
            ];
        }
	}

    public function generateInvoicePdf($orderId)
    {
        try {
            $user_id =2826;

            $order = MwPricePlanOrder::query()
                ->where('order_id', $orderId)
                ->where('customer_id', $user_id)
                ->with([
                    'mw_currency',
                    'mw_tax',
                    'mw_package_new',
                    'mw_listing_user',
                    'mw_price_plan_promo_code',
                    'mw_user_packages'
                ])
                ->first();
            
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found or access denied'
                ], 404);
            }

            $invoiceData = [
                'order' => $order,
                'invoice_number' => 'INV-' . str_pad($order->order_id, 6, '0', STR_PAD_LEFT),
                'invoice_date' => now()->format('Y-m-d'),
                'company_info' => MwOption::getCompanyInfo(),
                'customer_info' => [
                    'name' => ($order->mw_listing_user->first_name ?? '') . ' ' . ($order->mw_listing_user->last_name ?? ''),
                    'email' => $order->mw_listing_user->email ?? 'N/A',
                    'phone' => $order->mw_listing_user->mobile_number ?? 'N/A',
                ],
                'line_items' => [
                    [
                        'description' => $order->mw_package_new->package_name ?? 'Package Subscription',
                        'quantity' => 1,
                        // 'unit_price' => $order->amount ?? 0,
                        'total' => $order->subtotal ?? 0
                    ]
                ],
                'subtotal' => $order->subtotal ?? 0,
                'tax_amount' => $order->tax_value ?? 0,
                'discount_amount' => $order->discount ?? 0,
                'total_amount' => $order->total ?? 0,
                'payment_status' => $order->status,
                'payment_method' => $this->paymentArray($order->payment_type)
            ];

            $pdf = Pdf::loadView('invoices.template', $invoiceData);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'invoice_' . $order->order_id . '_' . now()->format('Y_m_d') . '.pdf';
            
            // return $pdf->stream($filename);
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate invoice PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function paymentArray($type)
    {
        return match($type) {
            't' => 'Credit Card',
            'b' => 'Bank transfer/Cash',
            default => 'Unknown'
        };
    }
}
