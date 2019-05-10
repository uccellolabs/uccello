<?php

namespace Uccello\Core\Http\Middleware;

use Closure;
use Auth;
use Uccello\Core\Models\Domain;

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

        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::firstOrFail();
        }

        $isModuleActive = $module->isActiveOnDomain($domain);

        // An user is allowed if he has the capability or if it is an admin module and the user can admin it
        $isUserAllowed = $user->hasCapabilityOnModule($capability, $domain, $module) || ($module->isAdminModule() && $user->canAdmin($domain, $module));

        if (!$isModuleActive) {
            return abort(404);
        }

        if (!$isUserAllowed) {
            // Try to redirect to a domain accessible by the user
            $domain = uccello()->useMultiDomains() ? uccello()->getLastOrDefaultDomain() : null;
            if ($domain && $user->canRetrieve($domain, ucmodule('home'))) {
                return redirect(ucroute('uccello.home', $domain));
            }

            return abort(403);
        }

        return $next($request);
    }
}
