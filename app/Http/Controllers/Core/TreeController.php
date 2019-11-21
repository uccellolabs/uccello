<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class TreeController extends Controller
{
    protected $viewName = 'tree.main';

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
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        if (!app('uccello')->isTreeModule($module)) {
            abort(404);
        }

        // Get model class
        $modelClass = $module->model_class;

        // Total count
        $totalCount = $modelClass::inDomain($domain, $this->request->session()->get('descendants'))->count();

        return $this->autoView(compact('totalCount'));
    }

    /**
     * Returns all root records where the user can access
     *
     * @return array
     */
    public function root(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get model class
        $modelClass = $module->model_class;

        // Get all roots records (according to the current domain)
        $rootRecords = $modelClass::getRoots()
            ->inDomain($domain, $this->request->session()->get('descendants'))
            ->get();

        $roots = collect();
        foreach ($rootRecords as $record) {
            $roots[] = $this->getFormattedRecordToAdd($record);
        }

        return $roots;
    }

    /**
     * Returns all record's children.
     *
     * @return array
     */
    public function children(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get model class
        $modelClass = $module->model_class;

        // Get parent record (according to the current domain)
        $parentRecord = $modelClass::inDomain($domain, $this->request->session()->get('descendants'))
            ->find(request('id'));

        $children = collect();
        if ($parentRecord) {
            foreach ($parentRecord->children()->get() as $record) {
                $children[] = $this->getFormattedRecordToAdd($record);
            }
        }

        return $children;
    }

    /**
     * Get formatted record to add to the tree
     *
     * @param mixed $record
     * @return array|null
     */
    protected function getFormattedRecordToAdd($record)
    {
        return [
            "id" => $record->getKey(),
            "text" => $record->recordLabel,
            "children" => $record->children->count() > 0,
            "a_attr" => [
                "href" => ucroute('uccello.detail', $this->domain, $this->module, [ 'id' => $record->getKey() ])
            ]
        ];
    }
}
