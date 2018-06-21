<?php

namespace Sardoj\Uccello\Http\Controllers\Core;

use Illuminate\Http\Request;
use Sardoj\Uccello\Models\Filter;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;
use Sardoj\Uccello\Http\Middleware\CheckPermissions;


class ListController extends Controller
{
    protected $viewName = 'list.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }

    /**
     * @inheritDoc
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get filter
        $filter = $this->getFilter();

        return $this->autoView(compact('filter'));
    }

    /**
     * Retrieve filter to apply for displaying the list of data
     *
     * @return Filter|null
     */
    protected function getFilter(): ?Filter
    {
        return Filter::where('module_id', $this->module->id)
            ->where('type', 'list')
            ->first();
    }
}
