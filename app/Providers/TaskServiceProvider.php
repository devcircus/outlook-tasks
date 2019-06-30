<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('types', function ($app) {
            return collect([
                'bag' => Type::where('type', 'swatch')->first(),
                'late' => Type::where('type', 'vsf')->first(),
                'new' => Type::where('type', 'prototype')->first(),
            ]);
        });
    }
}
