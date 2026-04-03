<?php

namespace App\Providers;

use App\Models\User;
use App\Repository\DBUsersRepository;
use App\Repository\DBCreditRepository;
use Illuminate\Support\ServiceProvider;

use App\Repository\DBNotificationRepository;
use App\Repository\DBExtraServicesRepository;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Repositoryinterface\UsersRepositoryinterface;
use App\Repositoryinterface\CreditRepositoryinterface;
use App\Repositoryinterface\NotificationRepositoryinterface;
use App\Repositoryinterface\ExtraServicesRepositoryinterface;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $repositories = [
            UsersRepositoryinterface::class                => DBUsersRepository::class,
            // CreditRepositoryinterface::class               => DBCreditRepository::class,
            // ExtraServicesRepositoryinterface::class        => DBExtraServicesRepository::class,
            // NotificationRepositoryinterface::class        => DBNotificationRepository::class,
        ];
        foreach ($repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            Paginator::useBootstrapFive(); // For Bootstrap 5

        Relation::morphMap([
            'driver' => User::class,
            'user'   => User::class
        ]);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payment_methods')) {
                $paymob = \App\Models\PaymentMethod::where('name', 'PAYMOB')->first();
                if ($paymob) {
                    config([
                        'paymob.api_key' => $paymob->api_key ?? config('paymob.api_key'),
                        'paymob.hmac_secret' => $paymob->hmac ?? config('paymob.hmac_secret'),
                        'paymob.integration_id' => $paymob->integration_id ?? config('paymob.integration_id'),
                        'paymob.wallet_integration_id' => $paymob->wallet_integration_id ?? config('paymob.wallet_integration_id'),
                        'paymob.iframe_id' => $paymob->iframe_id ?? config('paymob.iframe_id'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore DB errors during early artisan commands
        }
    }
}
