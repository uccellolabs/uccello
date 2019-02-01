<?php

namespace Uccello\Core\Http\Controllers\Preference;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\DetailController as DefaultDetailController;
use Uccello\Core\Models\Preference;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Widget;

class DetailController extends DefaultDetailController
{
    protected $viewName = 'detail.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Retrieve record from authenticated user id
        $recordId = auth()->id();

        // Selected tab
        $selectedTabId = (int)$request->input('tab');

        // Selected related list
        $selectedRelatedlistId = (int)$request->input('relatedlist');

        // Widgets
        $availableWidgets = Widget::where('type', 'summary')->get(); // TODO: Don't display widgets already added
        $widgets = $module->widgets()->get(); // TODO: Get wigets with priority (1. User 2. Domain 3. Default)

        return $this->autoView([
            'record' => $this->getRecord($recordId),
            'selectedTabId' => $selectedTabId,
            'selectedRelatedlistId' => $selectedRelatedlistId,
            'widgets' => $widgets,
            'availableWidgets' => $availableWidgets,
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
        return Preference::findOrNew(auth()->id());
    }
}
