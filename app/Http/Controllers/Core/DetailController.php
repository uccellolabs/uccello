<?php

namespace Sardoj\Uccello\Http\Controllers\Core;

use Illuminate\Http\Request;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;


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
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get record id
        $recordId = $request->input('id');

        return $this->autoView([
            'structure' => $this->getModuleStructure(),
            'record' => $this->getRecord($recordId)
        ]);
    }

    /**
     * Get module structure : tabs > blocks > fields
     * @return Module
     */
    protected function getModuleStructure()
    {
        return Module::find($this->module->id);
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

        try
        {
            $entityClass = $this->module->entity_class;
            $record = $entityClass::findOrFail($id);
        }
        catch (\Exception $e) {}

        return $record;
    }
}
