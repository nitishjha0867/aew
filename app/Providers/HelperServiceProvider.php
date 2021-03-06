<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// https://stackoverflow.com/a/31703148/5756755
class HelperServiceProvider extends ServiceProvider
{
    protected $helpers = [
        // 'stringHelper'
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->helpers as $helper) {
            $helper_path = app_path().'/Helpers/'.$helper.'.php';

            if (\File::isFile($helper_path)) {
                require_once $helper_path;
            }
        }
    }
}
