<?php

namespace Uccello\Core\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\UccelloController;

class ListController extends Controller
{
    use UccelloController;

    /**
     * Handles treatments.
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(?Domain $domain, Module $module, Request $request)
    {
        $this->preProcess($domain, $module, $request);
        return $this->process();
    }

    /**
     * Launches process.
     *
     * @return \Illuminate\Http\Request
     */
    protected function process()
    {
        dd($this->structure);
    }
}
