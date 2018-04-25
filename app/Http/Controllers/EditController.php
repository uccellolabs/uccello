<?php

namespace Sardoj\Uccello\Http\Controllers;

use Debugbar;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Cache;
use Kris\LaravelFormBuilder\FormBuilder;
use Sardoj\Uccello\Forms\EditForm;
use Sardoj\Uccello\Tab;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use Illuminate\Http\Request;


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
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        $entityClass = $this->module->entity_class;
        
            // Get record model
            $recordModel = new $entityClass();

        // Get form
        $form = $this->getForm($recordModel);

        return view($this->viewName, [
            'structure' => $this->getModuleStructure(),
            'form' => $form
        ]);
    }

    /**
     * Create or update record into database
     *
     * @param Domain $domain
     * @param Module $module
     * @return void
     */
    public function store(Domain $domain, Module $module)
    {
        // Pre-process
        $this->preProcess($domain, $module);        

        // Get entity class used by the module
        $entityClass = $this->module->entity_class;
        
        try
        {
            // Get record model
            $recordModel = new $entityClass();

            // Get form
            $form = $this->getForm($recordModel);

            // Redirect if form not valid
            $form->redirectIfNotValid();

            // Make record
            $values = $form->getFieldValues();
            foreach($values as $name => $value)
            {
                $form->getModel()->$name = $value;
            }

            // Save record
            $recordModel->save();

            // Redirect to detail view
            return redirect()->route('detail', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $recordModel->id]);
        }
        catch (\Exception $e) {}
            
        // If there was an error, redirect to edit page 
        // TODO: improve
        return redirect()->route('edit', ['domain' => $domain->slug, 'module' => $module->name]);
    }

    public function getForm($recordModel = null)
    {
        return $this->formBuilder->create(EditForm::class, [
            'model' => $recordModel,
            'data' => [
                'domain' => $this->domain,
                'module' => $this->module
            ]
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
