<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Admin\CarApiController;

use App\Http\Controllers\Api\V1\Admin\ChatApiController;

use App\Http\Controllers\Api\V1\Admin\OrderApiController;

use App\Http\Controllers\Api\V1\Admin\ServiceApiController;

use App\Http\Controllers\Api\V1\Admin\PaymentsApiController;

use App\Http\Controllers\Api\V1\Admin\AuthenticationController;
use App\Http\Controllers\Api\V1\Admin\DriverApiController;
use App\Http\Controllers\Api\V1\Admin\ReviewApiController;
use App\Http\Controllers\Api\V1\PackageApiController;


Route::post('test', [AuthenticationController::class, 'test']);

// Test endpoint for driver private channel broadcasting
Route::get('test-broadcast/{driver_id}/{trip_id}', function ($driver_id, $trip_id) {
    event(new \App\Events\TestDriverTripEvent($trip_id, $driver_id));
    return response()->json([
        'message' => 'Event dispatched successfully',
        'driver_id' => $driver_id,
        'trip_id' => $trip_id,
        'channel' => 'private-driver.'.$driver_id
    ]);
});

Route::get('test-trans', function () {
    app()->setLocale('ar');
    $trans = __('cruds.setting.fields.referral_bonus');
    $file_content = include resource_path('lang/ar/cruds.php');
    return response()->json([
        'trans' => $trans,
        'direct_file_access' => $file_content['setting']['fields']['referral_bonus'] ?? 'NOT FOUND',
        'locale' => app()->getLocale()
    ], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
});



Route::prefix('v1/user')->group(function () {
    Route::get('country', [AuthenticationController::class, 'country']);
    Route::get('city/{id}', [AuthenticationController::class, 'city']);
});


Route::prefix('v1/auth')->group(function () {
    // Rate limit: 60 signup attempts per minute per IP
    Route::post('signup', [AuthenticationController::class, 'signup'])->middleware('throttle:60,1');

    // Rate limit: 60 OTP verify attempts per minute per IP
    Route::get('verify_otp', [AuthenticationController::class, 'verify_otp'])->middleware('throttle:60,1');

    // Rate limit: 60 OTP sends per minute per IP
    Route::post('send_otp', [AuthenticationController::class, 'send_otp'])->middleware('throttle:60,1');
});
//'auth:sanctum'
Route::get('v1/send/chat', [ChatApiController::class, 'send_message']);
Route::get('v1/user/charge_wallet', [PaymentsApiController::class, 'charge_wallet']);
Route::get('v1/user/transactions', [PaymentsApiController::class, 'transactions']);

Route::get('v1/user/transactions', [PaymentsApiController::class, 'transactions']);
Route::get('v1/user/withdraw_request', [PaymentsApiController::class, 'withdraw_request']);
Route::get('v1/user/get_withdraw_request', [PaymentsApiController::class, 'get_withdraw_request']);

Route::get('v1/car/brands', [CarApiController::class, 'get_car_brands']);
Route::get('v1/car/models', [CarApiController::class, 'get_car_models']);

Route::get('country', [AuthenticationController::class, 'country']);
Route::get('city/{id}', [AuthenticationController::class, 'city']);

Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::get('settings', [AuthenticationController::class, 'settings']);
    Route::get('settings/percentage-increase', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'percentageIncrease']);
    Route::get('captions', [AuthenticationController::class, 'captions']);
    Route::get('faqs', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'index']);
    Route::get('contact-us', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'contactUs']);
    Route::get('pages', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'pages']);
    Route::match(['GET', 'POST'], 'write_us', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'write_us']);
    Route::post('settings/gamification', [App\Http\Controllers\Api\V1\Admin\SettingController::class, 'updateGamificationSettings']);

    // Unified Packages (Both for Driver and User)
    Route::get('packages', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'index']);
    Route::post('packages/buy', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'buy']);
    Route::get('packages/status', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'status']);

    // Unified Coupons (Both for /coupons and /user/coupons)
    Route::get('coupons', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'index']);
    Route::post('coupons/validate', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'validateCoupon']);

    Route::post('driver/registration', [DriverApiController::class, 'driver_registration']);
    Route::post('driver/update-service', [DriverApiController::class, 'change_service']);

    Route::prefix('driver')->group(function () {
        Route::get('earnings/summary', [\App\Http\Controllers\Api\V1\Admin\DriverEarningsApiController::class, 'summary']);
        Route::get('earnings/history', [\App\Http\Controllers\Api\V1\Admin\DriverEarningsApiController::class, 'history']);

        // Driver Gamification Store & Destination
        Route::post('set-destination', [\App\Http\Controllers\Api\V1\Admin\DriverApiController::class, 'setDestination']);
        Route::get('packages', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'index']);
        Route::post('packages/buy', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'buy']);
    });

    Route::prefix('user')->group(function () {
        // Route::get('charge_wallet', [AuthenticationController::class, 'charge_wallet']);
        Route::get('toggle_online/{online?}', [AuthenticationController::class, 'toggle_online']);
        Route::get('profile', [AuthenticationController::class, 'profile']);
        Route::post('profile/update', [AuthenticationController::class, 'profile_update']);
        Route::get('get-docs', [AuthenticationController::class, 'get_docs']);

        // Favorite Locations
        Route::prefix('favorite-locations')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\V1\UserFavoriteLocationController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\V1\UserFavoriteLocationController::class, 'store']);
            Route::match(['PUT', 'POST'], '/{id}', [\App\Http\Controllers\Api\V1\UserFavoriteLocationController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\V1\UserFavoriteLocationController::class, 'destroy']);
        });

        // User Shop Packages
        Route::get('packages', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'index']);
        Route::post('packages/buy', [\App\Http\Controllers\Api\V1\PackageApiController::class, 'buy']);

        // User Identity Verification APIs
        Route::post('verify-identity', [\App\Http\Controllers\Api\V1\UserIdentityApiController::class, 'verifyIdentity']);
        Route::get('identity-status', [\App\Http\Controllers\Api\V1\UserIdentityApiController::class, 'identityStatus']);

        // Coupons & Support Tickets
        Route::get('coupons', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'index']);
        Route::post('coupons/validate', [\App\Http\Controllers\Api\V1\CouponApiController::class, 'validateCoupon']);
        Route::post('tickets/create', [\App\Http\Controllers\Api\V1\SupportTicketApiController::class, 'createTicket']);
        Route::get('tickets/my-tickets', [\App\Http\Controllers\Api\V1\SupportTicketApiController::class, 'myTickets']);
    });
    Route::prefix('services')->group(function () {
        Route::get('incity', [ServiceApiController::class, 'incity']);
        Route::get('index', [ServiceApiController::class, 'index']);

        Route::get('outcity', [ServiceApiController::class, 'outcity']);
        Route::get('all', [ServiceApiController::class, 'all']);

        Route::get('rides', [ServiceApiController::class, 'rides']);
        Route::get('travels', [ServiceApiController::class, 'travels']);
        Route::get('shipping', [ServiceApiController::class, 'shipping']);
    });

    Route::prefix('order')->group(function () {
        Route::post('/new', [OrderApiController::class, 'neworder']);
        Route::get('/old-for-driver', [OrderApiController::class, 'get_driver_orders']);
        Route::get('/old-for-user', [OrderApiController::class, 'get_user_orders']);

        Route::get('/get-out-city-offers/{order_id}', [OrderApiController::class, 'get_out_city_offers']);

        Route::get('/get-driver-active-ride', [OrderApiController::class, 'get_driver_active_ride']);
        Route::get('/get-user-active-ride', [OrderApiController::class, 'get_user_active_ride']);
        Route::get('/show/{order}', [OrderApiController::class, 'show']);


        Route::get('/add-out-city-offer/{order_id}/{offer_rate}', [OrderApiController::class, 'add_out_city_offer']);
        Route::get('/current-orders-driver', [OrderApiController::class, 'current_orders_driver']);

        Route::get('/accept/{order}', [OrderApiController::class, 'acceptorder']);
        Route::get('/offer/{order}/{offer}', [OrderApiController::class, 'offerorder']);
        Route::get('/arrived/{order}', [OrderApiController::class, 'arrivedOrder']);
        Route::get('/start-moving/{order}', [OrderApiController::class, 'startMoving']);
        
        // Handover & OTP Verification APIs for Shipping & Normal Trips
        Route::post('/{order}/driver-arrive-sender', [OrderApiController::class, 'driverArriveSender']);
        Route::post('/{order}/verify-pickup-otp', [OrderApiController::class, 'verifyPickupOtp']);
        Route::post('/{order}/driver-arrive-receiver', [OrderApiController::class, 'driverArriveReceiver']);
        Route::post('/{order}/verify-delivery-otp', [OrderApiController::class, 'verifyDeliveryOtp']);
        Route::post('/{order}/verify-receiver-otp', [OrderApiController::class, 'verifyReceiverOtp']);

        // Test Realtime Updates (Inside Auth for production)
        Route::get('/test-status/{order}/{status}', [OrderApiController::class, 'updateTestStatus']);
        Route::post('/test-realtime-update', [OrderApiController::class, 'testRealtimeUpdate']);

        Route::get('/start/{order}', [OrderApiController::class, 'startorder']);
        Route::get('/end/{order}', [OrderApiController::class, 'endorder']);
        Route::get('/cancel/{order}', [OrderApiController::class, 'cancelorder']);
        Route::post('getprice', [OrderApiController::class, 'getprice']);
        Route::get('my', [OrderApiController::class, 'get_my_order']);

        // New Offer Negotiation Endpoints
        // Flow: User creates order with offer_rate ? Driver accepts OR counter-offers ? User accepts/denies
        Route::post('/offer/driver-counter', [OrderApiController::class, 'driverCounterOffer']);
        Route::post('/offer/driver-accept-user-price', [OrderApiController::class, 'driverAcceptUserPrice']);
        Route::post('/offer/{offer}/accept', [OrderApiController::class, 'acceptOffer']);
        Route::post('/offer/{offer}/deny', [OrderApiController::class, 'denyOffer']);
        Route::get('/{order}/offers', [OrderApiController::class, 'getOrderOffers']);
        Route::post('/{order}/resolve-payment', [OrderApiController::class, 'resolvePayment']);
        Route::post('/reject', [OrderApiController::class, 'rejectOrder']); // Driver reject order
        Route::get('/suggested-places', [OrderApiController::class, 'getSuggestedPlaces']);
    });
    Route::prefix('payments')->group(function () {
        Route::get('/get', [PaymentsApiController::class, 'get_payments']);
        Route::get('/change/{id}', [PaymentsApiController::class, 'check_payment']);
    });

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::post('/fcm-token', [\App\Http\Controllers\Api\V1\NotificationController::class, 'updateFcmToken']);
        Route::get('/', [\App\Http\Controllers\Api\V1\NotificationController::class, 'index']);
        Route::post('/{id}/read', [\App\Http\Controllers\Api\V1\NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [\App\Http\Controllers\Api\V1\NotificationController::class, 'markAllAsRead']);
        Route::get('/unread-count', [\App\Http\Controllers\Api\V1\NotificationController::class, 'unreadCount']);
    });

    // Admin Dashboard Routes - Notification system
    Route::prefix('admin')->group(function () {
        Route::post('/send-notification', [\App\Http\Controllers\Api\V1\Admin\AdminNotificationController::class, 'sendNotification']);
    });

    // Review Routes
    Route::prefix('review')->group(function () {
        // ????? ????? ?????
        Route::post('/order/{order_id}', [ReviewApiController::class, 'store']);

        // ??? ??????? ??????
        Route::get('/driver/{driver_id}', [ReviewApiController::class, 'getDriverReviews']);

        // ??? ??????? ????????
        Route::get('/user/{user_id}', [ReviewApiController::class, 'getUserReviews']);

        // ??? ????????? ???? ??? ??????
        Route::get('/my/given', [ReviewApiController::class, 'myGivenReviews']);

        // ??? ????????? ???? ??? ????????
        Route::get('/my/received', [ReviewApiController::class, 'myReceivedReviews']);

        // ?????? ?? ??????? ??????? ?????
        Route::get('/order/{order_id}/can-review', [ReviewApiController::class, 'canReview']);
    });
});

// Paymob Routes
Route::prefix('v1/paymob')->group(function () {
    // Public Webhook
    Route::post('/webhook', [\App\Http\Controllers\Api\V1\PaymobController::class, 'handleWebhook']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/payment-intention', [\App\Http\Controllers\Api\V1\PaymobController::class, 'createPaymentIntention']);
        Route::post('/add-card', [\App\Http\Controllers\Api\V1\PaymobController::class, 'addCard']);
        Route::get('/saved-cards', [\App\Http\Controllers\Api\V1\PaymobController::class, 'getSavedCards']);
        Route::get('/debug-cards', [\App\Http\Controllers\Api\V1\PaymobController::class, 'debugCards']);
        Route::delete('/saved-cards/{id}', [\App\Http\Controllers\Api\V1\PaymobController::class, 'deleteSavedCard']);
        Route::post('/pay-with-saved-card', [\App\Http\Controllers\Api\V1\PaymobController::class, 'payWithSavedCard']);
        Route::post('/charge-wallet', [\App\Http\Controllers\Api\V1\PaymobController::class, 'chargeWallet']);
    });
});

// Location Tracking Routes
Route::prefix('v1/location')->middleware('auth:sanctum')->group(function () {
    Route::post('/update', [\App\Http\Controllers\Api\V1\LocationTrackingController::class, 'updateLocation']);
    Route::get('/nearby', [\App\Http\Controllers\Api\V1\LocationTrackingController::class, 'getNearbyDrivers']); // New endpoint
    Route::get('/demand-map', [\App\Http\Controllers\Api\V1\DemandMapController::class, 'getDemandMap']);
    Route::get('/{user_id}', [\App\Http\Controllers\Api\V1\LocationTrackingController::class, 'getLocation']);
});

// Wallet Transfer Routes
Route::prefix('v1/wallet')->middleware('auth:sanctum')->group(function () {
    Route::post('/transfer', [\App\Http\Controllers\Api\V1\WalletTransferController::class, 'transferToUser']);
    Route::get('/info', [\App\Http\Controllers\Api\V1\WalletTransferController::class, 'getWalletInfo']);
});

Route::get('v1/user/points/history', [\App\Http\Controllers\Api\V1\PointTransactionController::class, 'history'])->middleware('auth:sanctum');

Route::get('test-db', function () {
    return response()->json([
        'raw_attributes' => \App\Models\Setting::first()->getAttributes()
    ]);
});