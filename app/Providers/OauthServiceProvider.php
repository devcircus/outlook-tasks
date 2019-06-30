<?php

namespace App\Providers;

use Microsoft\Graph\Graph;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Outlook\Query\QueryParameter;

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

        $this->app->singleton(Graph::class, function ($app) {
            return new Graph;
        });

        $this->app->singleton(QueryParameter::class, function ($app) {
            return new QueryParameter;
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
