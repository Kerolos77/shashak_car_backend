<?php


use App\Events\MessageSent;
use App\Http\Controllers\Admin\CaptionController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Api\V1\Admin\PaymentsApiController;
use App\Models\Page;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Marketopia\Admin\MarketopiaBrowserController;
use App\Models\Marketopia\MarketopiaCity;
use App\Models\Marketopia\MarketopiaCountry;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Websockets\UpdateDriverHandler;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AirportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Admin\IncomeController;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use App\Http\Controllers\Admin\WalletTransactionController;

// WebSocketsRouter::webSocket('/my-websocket', \App\MyCustomWebSocketHandler::class);
// Broadcast::routes();
Route::get('/account/delete/{id}', function ($id) {
    return 'SUCCESS';
});

Route::get('/track/{id}', [\App\Http\Controllers\PublicTrackingController::class, 'trackOrderPublic'])->name('orders.track_public');

Route::get('/page/{slug}', function ($slug) {
    $page = Page::where('slug', '=', $slug)->first();
    return view('welcome', compact('page'));
});

Route::get('test', function () {
    return  database_path('migrations');

});

WebSocketsRouter::webSocket('/socket/update-driver', UpdateDriverHandler::class);

Route::get('/payment/complete', function (Request $request) {
    $success = $request->query('success') === 'true';
    return view('payment_result', ['success' => $success, 'data' => $request->all()]);
})->name('payment.complete');

Route::get('welcome', function () {

    return view('welcome');
    // $user =  User::find(1);
    // $user->password = 'password';
    // $user->save();
});
Route::redirect('/', '/login');
Route::get('/payments/verify/{payment?}',[PaymentsApiController::class,'payment_verify'])->name('verify-payment');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::put('payment-methods', [PaymentMethodController::class, 'update'])->name('payment-methods.update');

    Route::get('settings/{id}', [SettingController::class, 'edit'])->name('settings.index');
    Route::post('settings/{id}', [SettingController::class, 'update'])->name('settings.update');

    // Permissions
    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::resource('countries',   CountryController::class);
    Route::put('countries/activate/{id}', [CountryController::class, 'activate'])->name('countries.activate');
    Route::put('countries/deactivate/{id}', [CountryController::class, 'deactivate'])->name('countries.deactivate');

    Route::resource('captions', CaptionController::class);


    Route::resource('cities', CityController::class);
    Route::put('cities/activate/{id}', [CityController::class, 'activate'])->name('cities.activate');
    Route::put('cities/deactivate/{id}', [CityController::class, 'deactivate'])->name('cities.deactivate');


    // Roles
    Route::resource('roles', RoleController::class);

    // Users Actions
    Route::get('users/{id}/export-pdf', [App\Http\Controllers\Admin\OfficialReportController::class, 'exportUserPdf'])->name('users.export-pdf');
    Route::post('users/{id}/toggle-vip', [UserController::class, 'toggleVip'])->name('users.toggle-vip');
    Route::post('users/{id}/add-wallet', [UserController::class, 'addWalletBalance'])->name('users.add-wallet');
    Route::resource('users', UserController::class);

    // Drivers Actions
    Route::get('drivers/{id}/export-pdf', [App\Http\Controllers\Admin\OfficialReportController::class, 'exportDriverPdf'])->name('drivers.export-pdf');
    Route::resource('drivers', DriverController::class);
    Route::put('drivers/activate/{id}', [DriverController::class, 'active'])->name('drivers.active');
    Route::put('drivers/block/{id}', [DriverController::class, 'block'])->name('drivers.block');
    Route::post('drivers/{id}/reset-cash-ban', [DriverController::class, 'resetCashBan'])->name('drivers.reset-cash-ban');
    Route::post('drivers/{id}/toggle-vip', [DriverController::class, 'toggleVip'])->name('drivers.toggle-vip');
    Route::post('drivers/{id}/add-wallet', [DriverController::class, 'addWalletBalance'])->name('drivers.add-wallet');
    Route::post('drivers/{id}/gift-package', [DriverController::class, 'giftPackage'])->name('drivers.gift-package');

    // Reviews Management
    Route::delete('reviews/{id}', [App\Http\Controllers\Admin\ReviewManagementController::class, 'destroy'])->name('reviews.destroy');

    // Airports
    Route::resource('airports', AirportController::class, ['except' => ['store', 'update', 'destroy']]);


    // Faq
    Route::resource('faqs', FaqController::class);

    // Orders
    Route::resource('orders', OrderController::class, ['except' => ['store', 'update', 'destroy']]);
    Route::get('manual-assign', [OrderController::class, 'manual_index'])->name('orders.manual_index');
    Route::get('manual-assign/{order}/drivers', [OrderController::class, 'manual_drivers'])->name('orders.manual_drivers');
    Route::post('manual-assign/{order}/assign', [OrderController::class, 'manual_assign'])->name('orders.manual_assign');

    // Shipping Orders
    Route::get('shipping-orders', [App\Http\Controllers\Admin\ShippingOrderController::class, 'index'])->name('shipping-orders.index');


    // Service
    Route::resource('services', ServiceController::class);

    // Wallet Transaction
    Route::resource('wallet-transactions', WalletTransactionController::class);
    Route::post('wallet-transactions/add-amount', [WalletTransactionController::class, 'add_amount'])->name('add_amount');

    // Expenses
    Route::post('expenses/sync', [\App\Http\Controllers\Admin\ExpenseController::class, 'sync'])->name('expenses.sync');
    Route::resource('expenses', \App\Http\Controllers\Admin\ExpenseController::class);

    // Audit Logs
    Route::resource('audit-logs', AuditLogController::class, ['except' => ['store', 'update', 'destroy', 'create', 'edit']]);

    // Admins
    Route::resource('admins', AdminController::class);


    Route::resource('pages', PageController::class);

    // create Marketopia Browser recourse route
    Route::resource('marketopia-browsers', MarketopiaBrowserController::class);

    // Chat
    Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('chats/single/{id}', [ChatController::class, 'single'])->name('chats.single');

    Route::get('payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('payments/requests', [PaymentsController::class, 'requests'])->name('payments.requests');
    Route::get('payments/{id}/accept', [PaymentsController::class, 'accept'])->name('payments.accept');
    Route::get('payments/{id}/reject', [PaymentsController::class, 'reject'])->name('payments.reject');


    Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');
    
    // Notifications Route
    Route::get('notifications/send', \App\Http\Livewire\Admin\SendNotification::class)->name('notifications.send');

    // Gamification Route
    Route::get('gamification', [App\Http\Controllers\Admin\GamificationController::class, 'index'])->name('gamification.index');
    Route::post('gamification', [App\Http\Controllers\Admin\GamificationController::class, 'update'])->name('gamification.update');

    // Packages (Shop) Route
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);

    // Dispatch & Restrictions Settings Route
    Route::get('dispatch-settings', [App\Http\Controllers\Admin\DispatchSettingController::class, 'index'])->name('dispatch-settings.index');
    Route::post('dispatch-settings', [App\Http\Controllers\Admin\DispatchSettingController::class, 'update'])->name('dispatch-settings.update');

    // Theme switching route
    Route::get('switch-theme/{theme}', function ($theme) {
        if (!in_array($theme, ['v1', 'v2'])) {
            abort(400);
        }
        session()->put('admin_theme', $theme);
        return redirect()->back();
    })->name('switch-theme');

});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    if (file_exists(app_path('Http/Controllers/Auth/UserProfileController.php'))) {
        Route::get('/', [UserProfileController::class, 'show'])->name('show');
    }
});

Route::get('lang/{locale}', function ($locale) {
    // $lang = $request->lang;

        if (!in_array($locale, ['en', 'ar'])) {
            abort(400);
        }

        Session::put('locale', $locale);

        return redirect()->back();
})->name('lang.switch');