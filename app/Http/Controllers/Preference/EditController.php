<?php

namespace Uccello\Core\Http\Controllers\Preference;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\EditController as DefaultEditController;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Preference;

class EditController extends DefaultEditController
{
    protected $viewName = 'edit.main';
    protected $formBuilder;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Retrieve record from authenticated user id
        $record = Preference::findOrNew(auth()->id());

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
    public function save(?Domain $domain, Module $module, Request $request, bool $redirect = true)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get model class used by the module
        $modelClass = $this->module->model_class;

        // Retrieve record from authenticated user id
        $record = Preference::findOrNew(auth()->id());


        // Get form
        $form = $this->getForm($record);

        // Redirect if form not valid (the record is made here)
        $form->redirectIfNotValid();

        $mode = 'edit';

        event(new BeforeSaveEvent($domain, $module, $request, $record, $mode));

        // Save record
        $form->getModel()->id = auth()->id();
        $form->getModel()->save();

        event(new AfterSaveEvent($domain, $module, $request, $record, $mode));

        // Redirect
        $route = ucroute('uccello.preference', $domain, $module);
        return redirect($route);
    }
}
