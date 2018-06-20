<?php

namespace Sardoj\Uccello\Http\Controllers;

use Debugbar;
use Kris\LaravelFormBuilder\FormBuilder;
use Sardoj\Uccello\Forms\EditForm;
use Illuminate\Http\Request;
use Sardoj\Uccello\Events\BeforeSaveEvent;
use Sardoj\Uccello\Events\AfterSaveEvent;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;


class EditController extends Controller
{
    protected $viewName = 'edit.main';
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

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest($request);

        // Get form
        $form = $this->getForm($record);

        return $this->autoView([
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
    public function store(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        // Get entity class used by the module
        $entityClass = $this->module->entity_class;

        try
        {
            // Retrieve record or get a new empty instance
            $record = $this->getRecordFromRequest($request);

            // Get form
            $form = $this->getForm($record);

            // Redirect if form not valid (the record is made here)
            $form->redirectIfNotValid();

            $mode = $record->id ? 'edit' : 'create';

            event(new BeforeSaveEvent($domain, $module, $request, $record, $mode));

            // Save record
            $form->getModel()->save();

            event(new AfterSaveEvent($domain, $module, $request, $record, $mode));

            // Redirect to detail view
            return redirect()->route('uccello.detail', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id]);
        }
        catch (\Exception $e) {}

        // If there was an error, redirect to edit page
        // TODO: improve
        return redirect()->route('uccello.edit', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id, 'error' => 1]);
    }

    public function getForm($record = null)
    {
        return $this->formBuilder->create(EditForm::class, [
            'model' => $record,
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
