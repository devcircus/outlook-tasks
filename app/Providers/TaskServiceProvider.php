<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use App\Services\Category\ListCategoriesService;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('definitionTypes', function ($app) {
            return [
                'from' => [
                    'id' => 1,
                    'name' => 'from',
                    'display_name' => 'From Definition',
                ],
                'subject' => [
                    'id' => 2,
                    'name' => 'subject',
                    'display_name' => 'Subject Definition',
                ],
                'body' => [
                    'id' => 3,
                    'name' => 'body',
                    'display_name' => 'Body Definition',
                ],
            ];
        });

        $this->app->singleton('categories', function ($app) {
            return ListCategoriesService::call($withTrashed = false)->mapWithKeys(function ($category) {
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
