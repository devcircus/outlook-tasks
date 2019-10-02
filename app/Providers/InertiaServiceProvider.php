<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Services\Category\ListCategoriesService;
use App\Services\Task\ListTaskQuantitiesService;
use App\Services\Email\ListEmailQuantitiesService;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->shareAssetVersion();
        $this->shareAuthenticatedUser();
        $this->shareAppInformation();
        $this->shareFlashMessages();
        $this->shareSessionErrors();
        $this->shareOutlookToken();
        $this->shareAppData();
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
     * Share the authenticated user with Inertia.
     */
    private function shareAuthenticatedUser(): void
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
            }
        ]);
    }

    /**
     * Share the app informatikon with Inertia.
     */
    private function shareAppInformation(): void
    {
        Inertia::share([
            'app' => static function () {
                return [
                    'name' => Config::get('app.name'),
                ];
            },
        ]);
    }

    /**
     * Share the session errors with Inertia.
     */
    private function shareSessionErrors(): void
    {
        Inertia::share([
            'errors' => function () {
                if ($errors = Session::get('errors')) {
                    $bags = $errors->getBags();

                    return collect($bags)->map(function ($bag, $key) {
                        return $bag->getMessages();
                    });
                }

                return (object) [];
            },
        ]);
    }

    /**
     * Share the flash messages with Inertia.
     */
    private function shareFlashMessages(): void
    {
        Inertia::share([
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                ];
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
        ]);
    }

    /**
     * Share the Outlook token with Inertia.
     */
    private function shareOutlookToken(): void
    {
        Inertia::share([
            'token' => static function () {
                if ($user = Auth::user()) {
                    return $user->outlook_access_token;
                }

                return '';
            },
        ]);
    }

    /**
     * Share app data with Inertia.
     */
    private function shareAppData(): void
    {
        Inertia::share([
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
            'taskQuantities' => static function () {
                if ($user = Auth::user()) {
                    return ListTaskQuantitiesService::call($user);
                }
            },
            'emailQuantities' => static function () {
                if ($user = Auth::user()) {
                    return ListEmailQuantitiesService::call($user);
                }
            },
        ]);
    }
}
