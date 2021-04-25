<?php

namespace Uccello\Core\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Repositories\ModuleRepository;
use Uccello\Core\Support\Traits\UccelloController;

class DetailController extends Controller
{
    use UccelloController;

    protected $record;

    /**
     * Handles treatments.
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(?Domain $domain, Module $module, $recordId, Request $request)
    {
        $this->preProcess($domain, $module, $request);

        $this->retrieveRecordById($recordId);

        return $this->process();
    }

    /**
     * Retrieves a record by id
     *
     * @param string|int $recordId
     *
     * @return void
     */
    protected function retrieveRecordById($recordId)
    {
        $repository = new ModuleRepository($this->module);
        $this->record = $repository->getById($recordId);
    }

    /**
     * Launches process.
     *
     * @return \Illuminate\Http\Request
     */
    protected function process()
    {
        dd($this->record);
    }
}
