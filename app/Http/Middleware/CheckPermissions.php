<?php

namespace Sardoj\Uccello\Http\Middleware;

use Closure;
use Auth;
use Cache;
use App\User;
use Sardoj\Uccello\Privilege;
use Sardoj\Uccello\Permission;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;

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
     */
    public function handle($request, Closure $next, string $capability)
    {
        $user = Auth::user();

        $domain = $request->domain;
        $module = $request->module;

        $isUserAllowed = $user->is_admin || $this->isUserAllowed($user, $capability, $domain, $module);

        if (!$isUserAllowed) {
            return abort(403);
        }

        return $next($request);
    }

    /**
     * Check if the user has a capability on a domain for a module.
     *
     * @param User $user
     * @param string $capability
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function isUserAllowed(User $user, string $capability, Domain $domain, Module $module): bool
    {
        $isAllowed = false;

        // Get capabilities on the domain from cache
        $cacheKey = "user_{$user->id}_domain_{$domain->id}_capabilities";
        $cacheTimeout = env('UCCELLO_CACHE_TIMEOUT', 10);

        $capabilities = Cache::remember($cacheKey, $cacheTimeout, function () use ($user, $domain, $module) {
            $capabilities = [];

            // Get user privileges on the domain
            $privileges = Privilege::where('domain_id', $domain->id)
                                    ->where('user_id', $user->id)
                                    ->get();

            // Get all user's permissions (Privilege -> Role -> Profiles -> Permissions)
            foreach ($privileges as $privilege) {
                foreach ($privilege->role->profiles as $profile) {
                    $profilePermissions = Permission::where('profile_id', $profile->id)
                                        ->get();
                    foreach ($profilePermissions as $profilePermission) {
                        $permissionModuleName = $profilePermission->module->name;
                        if (empty($capabilities[$permissionModuleName])) {
                            $capabilities[$permissionModuleName] = [];
                        }

                        $capabilities[$permissionModuleName][] = $profilePermission->capability;
                    }
                }
            }

            return $capabilities;
        });

        // Check if the user has the capability on the module
        if (count($capabilities) > 0 && isset($capabilities[$module->name])) {
            $isAllowed = in_array($capability, $capabilities[$module->name]);
        }

        return $isAllowed;
    }
}
