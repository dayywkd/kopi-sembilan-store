<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Register Brevo HTTP API mail transport.
        // Menggunakan HTTP API (port 443) agar tidak terblokir oleh Railway
        // yang memblokir outbound SMTP port 25/465/587.
        Mail::extend('brevo', function (array $config) {
            return (new BrevoTransportFactory())->create(
                new Dsn('brevo+api', 'default', $config['key'])
            );
        });
    }
}
