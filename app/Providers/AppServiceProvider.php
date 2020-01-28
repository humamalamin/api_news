<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Remove ignore if Authenticate middleware is used
 *
 * @codeCoverageIgnore
 */
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
}
