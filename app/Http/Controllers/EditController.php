<?php

namespace Sardoj\Uccello\Http\Controllers;

use Debugbar;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Cache;


class EditController extends Controller
{
    protected $viewName = 'uccello::edit.main';

    /**
     * {@inheritdoc}
     */
    public function process(string $domain, string $module)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        return view($this->viewName, [
            'structure' => $this->getModuleStructure()
        ]);
    }

    /**
     * Get module structure : tabs > blocks > fields
     * @return Module
     */
    public function getModuleStructure()
    {
        return Module::find($this->module->id);
    }
}
