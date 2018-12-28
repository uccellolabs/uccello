<?php

namespace Uccello\Core\Http\Middleware;

use Closure;
use Auth;

class CheckSettingsPanel
{
    /**
     * Check if the user can access to the settings panel or redirect to 403 page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $domain = $request->domain;

        if (!$user->canAccessToSettingsPanel($domain)) {
            return abort(403);
        }

        return $next($request);
    }
}
