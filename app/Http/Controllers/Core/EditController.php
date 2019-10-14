<?php

namespace Uccello\Core\Http\Controllers\Core;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Uccello\Core\Forms\EditForm;
use Uccello\Core\Events\BeforeSaveEvent;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Events\BeforeDeleteEvent;
use Uccello\Core\Events\AfterDeleteEvent;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
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
            $sourceRecordId = (int)$request->input('src_id');
            $tabId = $request->input('tab');

            if ($relatedlist->type === 'n-n') {
                $this->saveRelation($relatedlist, $sourceRecordId, $record->id);
            }

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
            $relatedRecordId = (int)$request->input('related_id');

            $relationId = $this->saveRelation($relatedlist, $record->id, $relatedRecordId);

            $response = [
                'success' => true,
                'data' => $relationId
            ];
        } else {
            $response = [
                'success' => false,
                'message' => uctrans('error.field.mandatory', $module)
            ];
        }

        return $response;
    }

    /**
     * Delete a relation between two records
     *
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     * @return integer|null
     */
    public function deleteRelation(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get related list if defined
        $relatedListId = $request->get('relatedlist');
        if ($relatedListId) {
            $relatedList = Relatedlist::find($relatedListId);
        } else {
            $relatedList = null;
        }

        // Delete relation if it is a N-N relation
        if ($relatedList && $relatedList->type === 'n-n') {
            $this->deleteRelationForNN($request);
            $message = 'notification.relation.deleted';
        }
        // Else delete record
        else {
            $this->deleteRecord($module, $request);
            $message = 'notification.record.deleted';
        }

        // Notification
        if (!empty($message)) {
            ucnotify(uctrans($message, $module), 'success');
        }

        // Redirect to the previous page
        $route = $this->getRedirectionRoute();

        return redirect($route);
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
     * @return void
     */
    protected function saveRelation(Relatedlist $relatedList, int $recordId, int $relatedRecordId)
    {
        $relationName = $relatedList->relationName;
        $modelClass = $relatedList->module->model_class;

        $record = $modelClass::find($recordId);

        if ($record) {
            $relation = $record->$relationName();
            $relation->attach($relatedRecordId);
        }
    }

    /**
     * Retrieve the redirection route from request parameters.
     *
     * @return string
     */
    protected function getRedirectionRoute(): string
    {
        $request = $this->request;
        $domain = $this->domain;
        $module = $this->module;

        // Retrieve a related list by its id if it is defined
        if ($request->input('relatedlist')) {
            $relatedlist = Relatedlist::find($request->input('relatedlist'));
        }

        // Redirect to source record if a relation was deleted
        if (isset($relatedlist) && $request->input('id')) {
            $params = ['id' => $request->input('id')];

            // Add tab id if defined to select it automaticaly
            if ($request->input('tab')) {
                $params['tab'] = $request->input('tab');
            }
            // Add related list id to select the related tab automaticaly
            else {
                $params['relatedlist'] = $relatedlist->id;
            }

            $route = ucroute('uccello.detail', $domain, $relatedlist->module, $params);
        }
        // Else redirect to list
        else {
            $route = ucroute('uccello.list', $domain, $module);
        }

        return $route;
    }

    /**
     * Delete record after retrieving from the request
     *
     * @param Module $module
     * @param Request $request
     * @return void
     */
    protected function deleteRecord(Module $module, Request $request)
    {
        $relatedRecord = $this->getRecordFromRequest('related_id');

        // Delete record if exists
        if ($relatedRecord) {
            event(new BeforeDeleteEvent($this->domain, $module, $request, $relatedRecord, 'delete'));

            $relatedRecord->delete();

            event(new AfterDeleteEvent($this->domain, $module, $request, $relatedRecord, 'delete'));
        }
    }

    /**
     * Delete a relation for a N-N related list
     *
     * @return void
     */
    protected function deleteRelationForNN(Request $request)
    {
        $relatedListId = $request->get('relatedlist');
        $recordId = $request->get('id');
        $relatedRecordId = $request->get('related_id');

        // Get related list
        $relatedList = Relatedlist::find($relatedListId);
        $relationName = $relatedList->relationName;

        if ($relatedList) {
            // Get record
            $modelClass = $relatedList->module->model_class;
            $record = $modelClass::find($recordId);

            // Delete relation
            if ($record) {
                $record->$relationName()->detach($relatedRecordId);
            }
        }
    }
}
