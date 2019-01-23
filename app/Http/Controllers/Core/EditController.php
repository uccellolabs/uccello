<?php

namespace Uccello\Core\Http\Controllers\Core;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Uccello\Core\Forms\EditForm;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relation;
use Uccello\Core\Models\Relatedlist;

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
    public function save(?Domain $domain, Module $module, Request $request, bool $redirect = true)
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

        // Save relation if necessary
        if ($request->input('relatedlist') && $request->input('src_id')) {
            $relatedlist = Relatedlist::findOrFail($request->input('relatedlist'));
            $sourceRecordId = $request->input('src_id');
            $tabId = $request->input('tab');

            $this->saveRelation($relatedlist, $sourceRecordId, $record->id);

            $redirectToSourceRecord = true;
        }

        // Redirect
        if ($redirect === true) {
            // Redirect to source record if a relation was made
            if (isset($relatedlist) && $redirectToSourceRecord === true) {
                $params = [ 'id' => $sourceRecordId ];

                // Add tab id if defined to select it automaticaly
                if ($tabId) {
                    $params[ 'tab' ] = $tabId;
                }
                // Add related list id to select the related tab automaticaly
                else {
                    $params[ 'relatedlist' ] = $relatedlist->id;
                }

                $route = ucroute('uccello.detail', $domain, $relatedlist->module, $params);
            }

            // Redirect to edit if the user want to create a new record
            elseif ($request->input('save_new_hdn') === '1') {
                $route = ucroute('uccello.edit', $domain, $module);

                // Notification
                ucnotify(uctrans('notification.record.created', $module), 'success');
            }
            // Else redirect to detail
            else {
                $route = ucroute('uccello.detail', $domain, $module, [ 'id' => $record->getKey() ]);
            }

            return redirect($route);
        }
        // Or return record
        else {
            return $form->getModel();
        }
    }

    /**
     * Add a relation between two records
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     * @return integer|null
     */
    public function addRelation(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Retrieve record or get a new empty instance
        $record = $this->getRecordFromRequest();

        if ($request->input('relatedlist') && $request->input('related_id')) {
            $relatedlist = Relatedlist::findOrFail($request->input('relatedlist'));
            $relatedRecordId = $request->input('related_id');

            $relationId = $this->saveRelation($relatedlist, $record->id, $relatedRecordId);

            $response = [
                'success' => true,
                'data' => $relationId
            ];
        } else {
            $response = [
                'success' => false,
                'message' => uctrans('error.mandatory.fields', $module)
            ];
        }

        return $response;
    }

    public function getForm($record = null)
    {
        return $this->formBuilder->create(EditForm::class, [
            'model' => $record,
            'data' => [
                'domain' => $this->domain,
                'module' => $this->module,
                'request' => $this->request
            ]
        ]);
    }

    /**
     * Save relation between two records
     *
     * @param Relatedlist $relatedList
     * @param integer $recordId
     * @param integer $relatedRecordId
     * @return integer
     */
    protected function saveRelation(Relatedlist $relatedList, int $recordId, int $relatedRecordId) : int
    {
        $relation = Relation::firstOrCreate([
            'module_id' => $relatedList->module_id,
            'related_module_id' => $relatedList->related_module_id,
            'record_id' => $recordId,
            'related_record_id' => $relatedRecordId,
            'relatedlist_id' => $relatedList->id
        ]);

        return $relation->id;
    }
}
