<?php

namespace Uccello\Core\Helpers;

use Illuminate\Support\Facades\Cache;
use Uccello\Core\Models\Uitype;

class Uccello
{
    /**
     * Returns true if multi workspaces option is activated, false else.
     *
     * @return boolean
     */
    public function useMultiWorkspaces()
    {
        return config('uccello.workspace.multi_workspaces');
    }

    /**
     * Get an Uitype instance by name or id
     *
     * @param string|int $nameOrId
     * @return Uitype|null
     */
    public function uitype($nameOrId): ?Uitype
    {
        if (!$nameOrId) {
            return null;
        }

        if (is_numeric($nameOrId)) {
            // Use cache
            $uitypes = Cache::remember('uitypes_by_id', now()->addMinutes(10), function () {
                $uitypesGroupedById = collect();
                Uitype::all()->map(function ($item) use ($uitypesGroupedById) {
                    $uitypesGroupedById[$item->id] = $item;
                    return $uitypesGroupedById;
                });
                return $uitypesGroupedById;
            });
            return $uitypes[(string) $nameOrId] ?? null;
        } else {
            // Use cache
            $uitypes = Cache::remember('uitypes_by_name', now()->addMinutes(10), function () {
                $uitypesGroupedByName = collect();
                Uitype::all()->map(function ($item) use ($uitypesGroupedByName) {
                    $uitypesGroupedByName[$item->name] = $item;
                    return $uitypesGroupedByName;
                });
                return $uitypesGroupedByName;
            });
            return $uitypes[(string) $nameOrId] ?? null;
        }
    }
}
