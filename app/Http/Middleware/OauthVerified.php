<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class OauthVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->hasOauthTokens()) {
            $request->session()->forget('warning');

            return $next($request);
        }

        $request->session()->put('warning', 'Outlook token is invalid. Please re-authorize.');

        return $next($request);
    }
}
