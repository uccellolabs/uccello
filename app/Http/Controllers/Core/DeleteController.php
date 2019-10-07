<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
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
        
        $this->deleteRecord($module, $request);
        $message = 'notification.record.deleted';

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

        return ucroute('uccello.list', $domain, $module);
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
}
