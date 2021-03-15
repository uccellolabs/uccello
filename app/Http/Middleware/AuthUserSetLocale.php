<?php

namespace Uccello\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthUserSetLocale
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
        if (Auth::check() && Auth::user()->userSettings) {
            // Locale
            $locale = Auth::user()->getSettings('locale', config('app.locale'));
            App::setLocale($locale);
        }

        return $next($request);
    }
}
