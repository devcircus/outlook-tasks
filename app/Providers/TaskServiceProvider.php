<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Category\ListCategoriesService;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('categories', function ($app) {
            return ListCategoriesService::call()->mapWithKeys(function ($category) {
                return [
                    $category->name => $category,
                ];
            });
        });

        $this->app->singleton('checkers', function ($app) {
            return resolve('categories')->map(function ($category, $key) {
                $name = ucfirst($key);

                return "\App\Services\Category\Checkers\CheckFor{$name}Category";
            });
        });
    }
}
