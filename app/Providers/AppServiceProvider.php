<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // non_present validation
        \Validator::extend('non_present', function($attribute, $value, $parameters, $validator)
        {
            // Validator::class;
            return !array_key_exists($attribute, $validator->getData());
        });
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
