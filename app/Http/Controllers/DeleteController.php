<?php

namespace Sardoj\Uccello\Http\Controllers;

use Illuminate\Http\Request;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;


class DeleteController extends Controller
{
    protected $viewName = null;

    /**
     * Delete record and redirect.
     * 
     * @param Domain $domain
     * @param Module $module
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        $record = $this->getRecordFromRequest($request);

        // Delete record if exists
        if ($record) {
            $record->delete();
        }

        return redirect()->route('uccello.list', ['domain' => $domain->slug, 'module' => $module->name]);
    }
}
