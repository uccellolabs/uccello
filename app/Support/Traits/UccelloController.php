<?php

namespace Uccello\Core\Support\Traits;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Repositories\RecordRepository;

trait UccelloController
{
    /**
     * @var \Uccello\Core\Models\Domain
     */
    protected $domain;

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
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function preProcess(?Domain $domain, Module $module, Request $request)
    {
        $this->domain = $domain ?? $this->retrieveFirstDomain();
        $this->module = $module;
        $this->request = $request;

        $this->loadModuleStructure();

        $this->instanciateRecordRepository();
    }

    /**
     * Retrieve first domain.
     *
     * @return void
     */
    private function retrieveFirstDomain()
    {
        $this->domain = Domain::first();
    }

    /**
     * Load module structure.
     *
     * @return void
     */
    private function loadModuleStructure()
    {
        if ($this->module) {
            $this->structure = $this->module->structure ?? null;
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