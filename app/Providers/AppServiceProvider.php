<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// fix - search mariadbissue in this file for more details
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* mariadbissue
         * since it's laravel 5.4, the default database charcter set is utf8mb4 to support emojis, which mariadb / older mysql versions don't support
         * below property fixes that issue
         * in the above issue, the warning will be shown as 'SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes'
         */
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
