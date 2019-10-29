<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Jenssegers\Agent\Agent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $agent = new Agent();
        $result = $agent->isMobile();
        config(["mobile"=>$result]);
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
