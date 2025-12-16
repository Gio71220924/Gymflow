<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\AppSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $settings = [];
        try {
            if (Schema::hasTable('app_settings')) {
                $settings = AppSetting::pluck('value', 'key');
            }
        } catch (\Throwable $e) {
            $settings = [];
        }
        View::share('appSettings', $settings);
    }
}
