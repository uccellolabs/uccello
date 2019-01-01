<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;


class DetailController extends Controller
{
    protected $viewName = 'detail.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get record id
        $recordId = $request->input('id');

        // Selected tab
        $selectedTabId = (int)$request->input('tab');

        // Selected related list
        $selectedRelatedlistId = (int)$request->input('relatedlist');

        return $this->autoView([
            'record' => $this->getRecord($recordId),
            'selectedTabId' => $selectedTabId,
            'selectedRelatedlistId' => $selectedRelatedlistId
        ]);
    }

    /**
     * Get record by id
     *
     * @param int $id
     * @return void
     */
    protected function getRecord(int $id)
    {
        $record = null;

        $modelClass = $this->module->model_class;
        $record = $modelClass::findOrFail($id);

        return $record;
    }
}
