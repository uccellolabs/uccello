<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Widget;
use Uccello\Core\Models\Relatedlist;

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
        $recordId = (int)$request->input('id');

        // Selected tab
        $selectedTabId = (int)$request->input('tab');

        // Selected related list
        $selectedRelatedlistId = (int)$request->input('relatedlist');

        // Check if the selected related list is visible
        if ($selectedRelatedlistId) {
            $relatedlist = Relatedlist::find($selectedRelatedlistId);
            if (empty($relatedlist) || !$relatedlist->isVisibleAsTab) {
                $selectedRelatedlistId = false;
            }
        }

        // Widgets
        $availableWidgets = Widget::where('type', 'summary')->get(); // TODO: Don't display widgets already added
        $widgets = $module->widgets()->withPivot('sequence')->orderBy('pivot_sequence')->get(); // TODO: Get wigets with priority (1. User 2. Domain 3. Default)

        return $this->autoView([
            'record' => $this->getRecord($this->domain, $module, $recordId),
            'selectedTabId' => $selectedTabId,
            'selectedRelatedlistId' => $selectedRelatedlistId,
            'widgets' => $widgets,
            'availableWidgets' => $availableWidgets,
        ]);
    }

     /**
      * Get record by id
      *
      * @param \Uccello\Core\Models\Domain|null $domain
      * @param \Uccello\Core\Models\Module $module
      * @param integer $id
      *
      * @return void
      */
    protected function getRecord(?Domain $domain, Module $module, int $id)
    {
        $record = null;

        $modelClass = $module->model_class;
        $record = $modelClass::findOrFail($id);

        return $record;
    }
}
