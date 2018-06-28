<?php

namespace OneSignal;

use Illuminate\Support\ServiceProvider;

class OneSignalProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/../config/onesignal.php' => config_path('onesignal.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        
    }
}
