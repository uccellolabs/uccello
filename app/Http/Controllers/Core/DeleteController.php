<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Events\BeforeDeleteEvent;
use Uccello\Core\Events\AfterDeleteEvent;

class DeleteController extends Controller
{
    protected $viewName = null;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:delete');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
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
            $this->deleteRelation($request);
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

    /**
     * Retrieve the redirection route from request parameters.
     *
     * @return string
     */
    protected function getRedirectionRoute() : string
    {
        $request = $this->request;
        $domain = $this->domain;
        $module = $this->module;

        // Retrieve a related list by its id if it is defined
        if ($request->input('relatedlist')) {
            $relatedlist = Relatedlist::find($request->input('relatedlist'));
        }

        // Redirect to source record if a relation was deleted
        if (isset($relatedlist) && $request->input('src_id')) {
            $params = [ 'id' => $request->input('src_id') ];

            // Add tab id if defined to select it automaticaly
            if ($request->input('tab')) {
                $params[ 'tab' ] = $request->input('tab');
            }
            // Add related list id to select the related tab automaticaly
            else {
                $params[ 'relatedlist' ] = $relatedlist->id;
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
        $record = $this->getRecordFromRequest();

        // Delete record if exists
        if ($record) {
            event(new BeforeDeleteEvent($this->domain, $module, $request, $record, 'delete'));

            $record->delete();

            event(new AfterDeleteEvent($this->domain, $module, $request, $record, 'delete'));
        }
    }

    /**
     * Delete a relation for a N-N related list
     *
     * @return void
     */
    protected function deleteRelation(Request $request)
    {
        $relatedListId = $request->get('relatedlist');
        $recordId = $request->get('src_id');
        $relatedRecordId = $request->get('id');

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
