<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Storage::extend('google', function ($app, $config) {
            $client = new \Google\Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            $client->addScope(\Google\Service\Drive::DRIVE);

            $service = new \Google\Service\Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder'] ?? '/', [
                'useDisplayPaths' => true,
            ]);

            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });

        \Illuminate\Support\Facades\View::composer('verifikator.*', function ($view) {
            try {
                $pendingKtp = \App\Models\IdentityVerification::where('status', 'pending')->count();
                $pendingProduk = \App\Models\Product::where('status', 'pending')->count();
                $pendingPembayaran = \App\Models\IdentityVerification::where('status', 'pending')->whereNotNull('payment_method')->count() 
                    + \App\Models\Order::where('payment_status', 'pending')->whereNotNull('payment_proof')->count();
                $laporanMasuk = \App\Models\Report::where('status', 'pending')->count();

                $view->with(compact('pendingKtp', 'pendingProduk', 'pendingPembayaran', 'laporanMasuk'));
            } catch (\Throwable $e) {
                // Fallback if database not initialized
            }
        });

        // Blade directive: @safeEmail($email) — hides bcrypt hashes & non-email strings
        Blade::directive('safeEmail', function ($expression) {
            return "<?php
                \$_safeEmailVal = {$expression};
                echo (!empty(\$_safeEmailVal)
                    && is_string(\$_safeEmailVal)
                    && str_contains(\$_safeEmailVal, '@')
                    && !str_starts_with(\$_safeEmailVal, '\$2y\$')
                    && !str_starts_with(\$_safeEmailVal, '\$2a\$')
                    && !str_starts_with(\$_safeEmailVal, '\$2b\$')
                    && !str_starts_with(\$_safeEmailVal, '\$argon')
                    && !str_starts_with(\$_safeEmailVal, '$'))
                    ? e(\$_safeEmailVal)
                    : '-';
            ?>";
        });
    }
}