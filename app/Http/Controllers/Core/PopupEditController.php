<?php

namespace Uccello\Core\Http\Controllers\Core;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Uccello\Core\Forms\PopupEditForm;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;

class PopupEditController extends Controller
{
    protected $viewName = 'edit.popup';
    protected $formBuilder;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {

        $this->middleware('uccello.permissions:create');

    }

    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;

        parent::__construct();
    }

    /**
     * Create or update record into database
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param boolean $redirect
     * @return void
     */
    public function save(?Domain $domain, Module $module, Request $request, bool $redirect = false)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();

        // Get form
        $form = $this->getForm($domain, $module, $request, $record);

        // Redirect if form not valid (the record is made here)
        $form->redirectIfNotValid();

        $mode = 'create';

        $model = $form->getModel();

        event(new BeforeSaveEvent($domain, $module, $request, $model, $mode));
        
        if(uccello()->useMultiDomains())
            $model->domain_id = $domain->id;
            
        // Save record
        $model->save();

        event(new AfterSaveEvent($domain, $module, $request, $model, $mode));

        return [
            'id' => $model->id,
            'display_name' => $model->recordLabel
        ];
    }

    public function getForm($domain, $module, $request, $record = null)
    {
        if($record==null)
            $record = new \App\Organisation;
        return $this->formBuilder->create(PopupEditForm::class, [
            'model' => $record,
            'data' => [
                'domain' => $domain,
                'module' => $module,
                'request' => $request
            ]
        ]);
    }
}
