<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relation;
use Uccello\Core\Models\Relatedlist;


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

        // Retrieve a relation if relation_id is defined
        if ($request->input('relation_id')) {
            $relationId = $request->input('relation_id');
            $record = Relation::find($relationId);
        }
        // Else retrieve a record by its id
        else {
            $record = $this->getRecordFromRequest();
        }

        // Delete record if exists
        if ($record) {
            $record->delete();

            // Notification
            ucnotify(uctrans('notification.record.deleted', $module), 'success');
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
            $params = ['id' => $request->input('src_id')];

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
}
