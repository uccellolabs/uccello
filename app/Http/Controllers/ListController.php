<?php

namespace Sardoj\Uccello\Http\Controllers;

use Sardoj\Uccello\Filter;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Http\Request;


class ListController extends Controller
{
    protected $viewName = 'uccello::list.main';

    /**
     * @inheritDoc
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        // Get filter
        $filter = $this->getFilter();

        return view($this->viewName, compact('filter'));
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
