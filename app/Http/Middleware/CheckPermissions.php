<?php

namespace Sardoj\Uccello\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Sardoj\Uccello\Models\User;
use Sardoj\Uccello\Models\Privilege;
use Sardoj\Uccello\Models\Permission;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;

class CheckPermissions
{
    /**
     * Check if the user has permission to access the asked page or redirect to 403 page.
     * Rule: An user is allowed if he is admin or if he has the asked capability.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $capability
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next, string $capability)
    {
        $user = Auth::user();

        $domain = $request->domain;
        $module = $request->module;

        $isModuleActive = $module->isActiveOnDomain($domain);
        $isUserAllowed = $user->is_admin || $user->hasCapabilityOnModule($capability, $domain, $module);

        if (!$isModuleActive) {
            return abort(404);
        }

        if (!$isUserAllowed) {
            return abort(403);
        }

        return $next($request);
    }
}
