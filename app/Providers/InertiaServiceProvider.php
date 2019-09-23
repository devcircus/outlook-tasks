<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Services\Category\ListCategoriesService;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->shareAssetVersion();
        $this->shareWithInertia();
    }

    /**
     * Share asset version with Inertia.
     */
    protected function shareAssetVersion(): void
    {
        Inertia::version(static function () {
            return md5_file(public_path('mix-manifest.json'));
        });
    }

    /**
     * Configure and share data with Inertia.
     */
    protected function shareWithInertia(): void
    {
        Inertia::share([
            'auth' => function () {
                $user = Auth::user();

                return [
                    'user' => $user ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin,
                        'has_oauth_tokens' => $user->has_oauth_tokens,
                        'token' => $user->outlook_access_token,
                    ] : null,
                ];
            },
            'app' => static function () {
                return [
                    'name' => Config::get('app.name'),
                ];
            },
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                ];
            },
            'errors' => function () {
                if ($errors = Session::get('errors')) {
                    $bags = $errors->getBags();

                    return collect($bags)->map(function ($bag, $key) {
                        return $bag->getMessages();
                    });
                }

                return (object) [];
            },
            'success' => static function () {
                return [
                    'success' => Session::get('success'),
                ];
            },
            'warning' => static function () {
                return [
                    'warning' => Session::get('warning'),
                ];
            },
            'info' => static function () {
                return [
                    'info' => Session::get('info'),
                ];
            },
            'session' => static function () {
                return [
                    'session' => Session::get('session'),
                ];
            },
            'token' => static function () {
                if ($user = Auth::user()) {
                    return $user->outlook_access_token;
                }

                return '';
            },
            'categories' => static function () {
                if (Auth::user()) {
                    $categories = ListCategoriesService::call();

                    return [
                        'data' => $categories,
                        'ready' => count($categories) > 0,
                    ];
                }
            },
            'definitionTypes' => static function () {
                if (Auth::user()) {
                    return resolve('definitionTypes');
                }
            },
        ]);
    }
}
