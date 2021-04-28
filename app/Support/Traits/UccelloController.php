<?php

namespace Uccello\Core\Support\Traits;

use Illuminate\Http\Request;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Models\Module;
use Uccello\Core\Repositories\RecordRepository;

trait UccelloController
{
    /**
     * @var \Uccello\Core\Models\Workspace
     */
    protected $workspace;

    /**
     * @var \Uccello\Core\Models\Module
     */
    protected $module;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \stdClass
     */
    protected $structure;

    /**
     * @var \Uccello\Core\Repositories\RecordRepository;
     */
    protected $repository;

   /**
     * Initialize local variables.
     *
     * @param \Uccello\Core\Models\Workspace|null $workspace
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function preProcess(?Workspace $workspace, Module $module, Request $request)
    {
        $this->workspace = $workspace ?? $this->retrieveFirstWorkspace();
        $this->module = $module;
        $this->request = $request;

        $this->loadModuleStructure();

        $this->instanciateRecordRepository();
    }

    /**
     * Retrieve first workspace.
     *
     * @return void
     */
    private function retrieveFirstWorkspace()
    {
        $this->workspace = Workspace::first();
    }

    /**
     * Load module structure.
     *
     * @return void
     */
    private function loadModuleStructure()
    {
        if ($this->module) {
            $this->structure = $this->module->data->structure ?? null;
        }
    }

    /**
     * Instanciate module repository.
     *
     * @return void
     */
    private function instanciateRecordRepository()
    {
        $this->repository = new RecordRepository($this->module);
    }
}
