<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\GenericProvider;

class OauthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(GenericProvider::class, function ($app) {
            return new GenericProvider([
                'clientId' => config('services.outlook.app_id'),
                'clientSecret' => config('services.outlook.client_secret'),
                'redirectUri' => config('services.outlook.redirect_uri'),
                'urlAuthorize' => config('services.outlook.url_authorize'),
                'urlAccessToken' => config('services.outlook.url_access_token'),
                'urlResourceOwnerDetails' => '',
                'scopes'  => config('services.outlook.scopes'),
            ]);
        });

        $this->app->alias(GenericProvider::class, 'oauth');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
