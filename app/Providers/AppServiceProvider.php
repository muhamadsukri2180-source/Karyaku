<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
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
        Storage::extend('google', function($app, $config) {
            $client = new \Google\Client();
            if (!empty($config['serviceAccountJson'])) {
                $client->setAuthConfig($config['serviceAccountJson']);
            }
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
    }
}