<?php

namespace Uccello\Core\Http\Controllers\Core;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Uccello\Core\Forms\EditForm;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class EditController extends Controller
{
    protected $viewName = 'edit.main';
    protected $formBuilder;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        if (request()->has('id')) {
            $this->middleware('uccello.permissions:update');
        } else {
            $this->middleware('uccello.permissions:create');
        }
    }

    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();

        // Get form
        $form = $this->getForm($record);

        // Get mode
        $mode = !is_null($record->getKey()) ? 'edit' : 'create';

        return $this->autoView([
            'form' => $form,
            'record' => $record,
            'mode' => $mode
        ]);
    }

    /**
     * Create or update record into database
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param boolean $redirect
     * @return void
     */
    public function save(?Domain $domain, Module $module, Request $request, bool $redirect=true)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get model class used by the module
        $modelClass = $this->module->model_class;

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();

        // Get form
        $form = $this->getForm($record);

        // Redirect if form not valid (the record is made here)
        $form->redirectIfNotValid();

        $mode = $record->getKey() ? 'edit' : 'create';

        event(new BeforeSaveEvent($domain, $module, $request, $record, $mode));

        // Save record
        $form->getModel()->save();

        event(new AfterSaveEvent($domain, $module, $request, $record, $mode));

        // Redirect to detail view
        if ($redirect === true) {
            // Redirect to edit if the user want to create a new record
            if ($request->input('save_new_hdn') === '1') {
                $route = ucroute('uccello.edit', $domain, $module);
            }
            // Else redirect to detail
            else {
                $route = ucroute('uccello.detail', $domain, $module, ['id' => $record->getKey()]);
            }

            return redirect($route);
        }
        // Or return record
        else {
            return $form->getModel();
        }
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
}
