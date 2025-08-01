<?php

use App\Http\Controllers\MwPlaceAnAdController;
use App\Http\Controllers\MwContactUController;
use App\Http\Controllers\MwCategoryController;
use App\Http\Controllers\MwSellHomeController;
use App\Http\Controllers\MwListingUserController;
use App\Http\Controllers\MwArticleController;
use App\Http\Controllers\MwArticleCategoryController;
use App\Http\Controllers\MwAreaGuideController;
use App\Http\Controllers\MwAdFavouriteController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MwAgentReviewController;
use App\Http\Controllers\MwPackageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricePlanOrderController;
use App\Http\Controllers\UserPackageController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUploadController;

// Route::middleware(['auth:sanctum'])->prefix('stripe')->group(function () {
//     Route::post('/subscription/checkout', [StripeController::class, 'subscriptionCheckout']);
//     Route::get('/subscription/success', [StripeController::class, 'subscriptionSuccess'])->name('subscription.success');
//     Route::get('/subscription/cancel', [StripeController::class, 'subscriptionCancel'])->name('subscription.cancel');
//     Route::get('/subscription/status', [StripeController::class, 'subscriptionStatus']);
//     Route::post('/subscription/cancel', [StripeController::class, 'cancelSubscription']);
//     Route::post('/subscription/resume', [StripeController::class, 'resumeSubscription']);
//     Route::get('/customer-portal', [StripeController::class, 'customerPortal']);
//     Route::get('/packages', [StripeController::class, 'getPackages']);
//     Route::get('/subscription-products', [StripeController::class, 'getSubscriptionProducts']);
// });

// Stripe webhook endpoint
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook'])->name('stripe.webhook');



Route::middleware('throttle:6,1')->group(function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
    Route::post('/signin', [AuthController::class, 'signIn']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});


Route::middleware('throttle:3,1')->group(function () {
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'reSendOtp']);
    Route::post('/verify-phone', [AuthController::class, 'verifyPhone']);
});

Route::middleware('throttle:60,1')->group(function () {
    Route::prefix('listings')->group(function () {
            Route::get('/languages', [ListingController::class, 'languages']);
            Route::get('/services', [ListingController::class, 'services']);
            Route::get('/countries', [ListingController::class, 'countries']);
            Route::get('/states', [ListingController::class, 'states']);
            Route::get('/cities', [ListingController::class, 'cities']);
            Route::get('/categories', [ListingController::class, 'categories']);
            Route::get('/developers', [ListingController::class, 'developers']);
            Route::get('/amenities', [ListingController::class, 'amenities']);
            Route::get('/countries/{countryId}/states', [ListingController::class, 'statesByCountry']);
            Route::get('/states/{stateId}/cities', [ListingController::class, 'citiesByState']);
            Route::get('/cities/{city_id}/community', [ListingController::class, 'community']);
           

    });

    Route::prefix('listing-users')->group(function () {
            Route::get('/', [MwListingUserController::class, 'index']);
            Route::get('/{id}', [MwListingUserController::class, 'show']);
    });

    Route::prefix('place-an-ad')->group(function () {
            Route::get('/', [MwPlaceAnAdController::class, 'index']);
            Route::get('/user-ads', [MwPlaceAnAdController::class, 'agencyAndAgentAds']);
            Route::get('/{id}', [MwPlaceAnAdController::class, 'show']);
    });

    Route::get('articles', [MwArticleController::class, 'index']);
    Route::get('articles/{id}', [MwArticleController::class, 'show']);
    Route::get('article-categories', [MwArticleCategoryController::class, 'index']);
    Route::get('agent-reviews', [MwAgentReviewController::class, 'index']);
    Route::get('agent-reviews/{id}', [MwAgentReviewController::class, 'show']);
    Route::post('agent-reviews', [MwAgentReviewController::class, 'store']);
    Route::get('categories', [MwCategoryController::class, 'index']);
    Route::get('specializations', [MwCategoryController::class, 'getUserSpecializations']);
    Route::get('area-guides', [MwAreaGuideController::class, 'index']);
    Route::get('area-guides/{id}', [MwAreaGuideController::class, 'show']);
    Route::get('favourites', [MwAdFavouriteController::class, 'index']);
    Route::get('/packages', [MwPackageController::class, 'index']);
    

});

Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'check.user.status'])->group(function () {
  
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/upload-file', [FileUploadController::class, 'uploadFile']);
    Route::delete('/delete-file', [FileUploadController::class, 'deleteFile']);



    Route::prefix('listing-users')->group(function () {
            Route::post('/', [MwListingUserController::class, 'store']);
            Route::put('/{id}', [MwListingUserController::class, 'update']);
            Route::patch('/change-password', [MwListingUserController::class, 'changePassword']);
            Route::patch('/approve-agency', [MwListingUserController::class, 'approveAgency']);
    });


    Route::prefix('user-packages')->group(function () {
        Route::get('/', [UserPackageController::class, 'index']);
        Route::get('/active', [UserPackageController::class, 'active']);
        Route::get('/expired', [UserPackageController::class, 'expired']);
        Route::get('/summary', [UserPackageController::class, 'summary']);
        Route::get('/{id}', [UserPackageController::class, 'show']);
        Route::post('/{id}/cancel', [UserPackageController::class, 'cancel']);
    });

    Route::middleware('throttle:60,1')->group(function () {
            Route::apiResource('place-an-ad', MwPlaceAnAdController::class)->except(['index','show']);
            Route::patch('/change-password', [MwListingUserController::class, 'changePassword']);
            Route::get('inquiries', [MwContactUController::class, 'index']);
            Route::get('inquiries/{id}', [MwContactUController::class, 'show']);
            Route::post('contact', action: [MwContactUController::class, 'EnquiryCreate']);
            Route::patch('inquiries/{id}/mark-read', [MwContactUController::class, 'markAsRead']);
            Route::patch('inquiries/{id}/mark-unread', [MwContactUController::class, 'markAsUnread']);
            Route::post('favourites', [MwAdFavouriteController::class, 'store']);
            Route::delete('favourites', [MwAdFavouriteController::class, 'destroy']);
            
            
            Route::put('agent-reviews/{id}', [MwAgentReviewController::class, 'update']);
            Route::delete('agent-reviews/{id}', [MwAgentReviewController::class, 'destroy']);
            Route::patch('agent-reviews/{id}/approve', [MwAgentReviewController::class, 'approveReview']);
            Route::patch('agent-reviews/{id}/reject', [MwAgentReviewController::class, 'rejectReview']);
            Route::get('/statistics', [StatisticsController::class, 'index']);

            Route::get('/billing', [PricePlanOrderController::class, 'index']);
            Route::get('/manage-subscription', [PricePlanOrderController::class, 'manage_subscription']);
            Route::get('/billing/{id}', [PricePlanOrderController::class, 'show']);
            Route::get('/billing/{id}/invoice', [PricePlanOrderController::class, 'generateInvoicePdf']);
           

            Route::post('/subscription-checkout', [StripeController::class, 'subscriptionCheckout']);
    
            // Subscription management
            Route::get('/subscription/status', [StripeController::class, 'subscriptionStatus']);
            Route::post('/subscription/cancel', [StripeController::class, 'cancelSubscription']);
            Route::post('/subscription/resume', [StripeController::class, 'resumeSubscription']);
            Route::post('/customer-portal', [StripeController::class, 'customerPortal']);
            
            // Product/Package routes
            // Route::get('/packages', [StripeController::class, 'getPackages']);
            Route::get('/subscription-products', [StripeController::class, 'getSubscriptionProducts']);
            Route::get('listings/user-cities', [ListingController::class, 'userCities']);
            // Route::patch('agent-reviews/bulk-approve', [MwAgentReviewController::class, 'bulkApprove']);
            // Route::patch('agent-reviews/bulk-reject', [MwAgentReviewController::class, 'bulkReject']);
            
            // Route::patch('inquiries/bulk-mark-read', [MwContactUController::class, 'bulkMarkAsRead']);
        });
        
        
        
});



// Test email route using Laravel Mail facade
Route::get('/test-email-laravel', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email sent via Resend using Laravel Mail facade!', function ($message) {
            $message->to('yahyaimran1999@gmail.com') 
                    ->subject('Test Email via Resend & Laravel Mail')
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully via Laravel Mail + Resend!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email',
            'error' => $e->getMessage()
        ], 500);
    }
});


