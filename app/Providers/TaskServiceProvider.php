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
        $this->app->singleton('definitionTypes', function ($app) {
            return [
                'fromDefinition' => [
                    'id' => 1,
                    'name' => 'fromDefinition',
                    'display_name' => 'From Definition',
                ],
                'subjectDefinition' => [
                    'id' => 2,
                    'name' => 'SubjectDefinition',
                    'display_name' => 'Subject Definition',
                ],
                'bodyDefinition' => [
                    'id' => 3,
                    'name' => 'BodyDefinition',
                    'display_name' => 'Body Definition',
                ],
            ];
        });

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
