<?php

namespace Uccello\Core\Http\Controllers\Group;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\EditController as CoreEditController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Illuminate\Support\Facades\Artisan;

class EditController extends CoreEditController
{
    /**
     * Add a relation between two records
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     * @return integer|null
     */
    public function addRelation(?Domain $domain, Module $module, Request $request)
    {
        $result = parent::addRelation($domain, $module, $request);

        Artisan::call('cache:clear');

        return $result;
    }

    /**
     * Delete a relation between two records
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     * @return integer|null
     */
    public function deleteRelation(?Domain $domain, Module $module, Request $request)
    {
        $result = parent::deleteRelation($domain, $module, $request);

        Artisan::call('cache:clear');

        return $result;
    }
}
