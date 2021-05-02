<?php

namespace Uccello\Core\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Workspace;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\UccelloController;

class ListController extends Controller
{
    use UccelloController;

    /**
     * Handle treatments.
     *
     * @param \Uccello\Core\Models\Workspace|null $workspace
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(?Workspace $workspace, Module $module, Request $request)
    {
        $this->preProcess($workspace, $module, $request);

        return $this->process();
    }

    /**
     * Launch process.
     *
     * @return \Illuminate\Http\Response
     */
    protected function process()
    {
        $viewName = $this->getViewNameToUse();

        return view($viewName, [
            'workspace' => $this->workspace,
            'module' => $this->module
        ]);
    }

    /**
     * Retrieve view name to use.
     *
     * @return string|null
     */
    private function getViewNameToUse()
    {
        return Uccello::view(
            $this->module->package,
            $this->module,
            'list.main',
            'uccello::modules.default.list.main'
        );
    }
}
