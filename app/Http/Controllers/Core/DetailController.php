<?php

namespace Uccello\Core\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\UccelloController;

class DetailController extends Controller
{
    use UccelloController;

    protected $record;

    /**
     * Handle treatments.
     *
     * @param \Uccello\Core\Models\Workspace|null $workspace
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(?Workspace $workspace, Module $module, $recordId, Request $request)
    {
        $this->preProcess($workspace, $module, $request);

        $this->retrieveRecordById($recordId);

        return $this->process();
    }

    /**
     * Retrieve a record by id
     *
     * @param string|int $recordId
     *
     * @return void
     */
    protected function retrieveRecordById($recordId)
    {
        $this->record = $this->repository->getById($recordId);
    }

    /**
     * Launch process.
     *
     * @return \Illuminate\Http\Response
     */
    protected function process()
    {
        return response()->json($this->record);
    }
}
