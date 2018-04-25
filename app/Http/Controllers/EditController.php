<?php

namespace Sardoj\Uccello\Http\Controllers;

use Debugbar;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Cache;
use Kris\LaravelFormBuilder\FormBuilder;
use Sardoj\Uccello\Forms\EditForm;
use Sardoj\Uccello\Tab;


class EditController extends Controller
{
    protected $viewName = 'uccello::edit.main';
    protected $formBuilder;

    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function process(Domain $domain, Module $module)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        $form = $this->formBuilder->create(EditForm::class, [
            'data' => [
                'module' => $this->module
            ]
        ]);

        return view($this->viewName, [
            'structure' => $this->getModuleStructure(),
            'form' => $form
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
}
