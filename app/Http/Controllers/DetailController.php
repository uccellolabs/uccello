<?php

namespace Sardoj\Uccello\Http\Controllers;

use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Http\Request;


class DetailController extends Controller
{
    protected $viewName = 'uccello::detail.main';

    /**
     * {@inheritdoc}
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        // Get record id
        $recordId = $request->input('id');

        return view($this->viewName, [
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
