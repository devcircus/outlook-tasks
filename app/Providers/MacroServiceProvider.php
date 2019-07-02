<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\CarbonImmutable as Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->registerRequestMacros();
        $this->registerCarbonMacros();
        $this->registerCollectionMacros();
        $this->registerBuilderMacros();
    }

    /**
     * Register Request macros.
     */
    private function registerRequestMacros()
    {
        Request::mixin(new \App\Macros\Request);
    }

    /**
     * Register Carbon macros.
     */
    private function registerCarbonMacros()
    {
        Carbon::mixin(new \App\Macros\Carbon);
    }

    /**
     * Register Collection macros.
     */
    private function registerCollectionMacros()
    {
        Collection::mixin(new \App\Macros\Collection);
    }

    /**
     * Register Builder macros.
     */
    private function registerBuilderMacros()
    {
        Builder::macro('search', function ($attributes, string $searchTerms) {
            $this->where(function (Builder $query) use ($attributes, $searchTerms) {
                foreach (array_wrap($attributes) as $attribute) {
                    $query->orWhere(function ($query) use ($attribute, $searchTerms) {
                        foreach (explode(' ', $searchTerms) as $searchTerm) {
                            $query->where($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    });
                }
            });

            return $this;
        });
    }
}
