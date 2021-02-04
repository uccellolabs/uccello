<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Http\Request;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class SummaryController extends Controller
{
    protected $viewName = null;

    /**
     * Check user permissions
     */
    // protected function checkPermissions()
    // {
    //     $this->middleware('uccello.permissions:delete');
    // }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $this->savePosition($domain, $module, $request);
        $message = 'Widget position saved';

        // Notification
        if (!empty($message)) {
            ucnotify(uctrans($message, $module), 'success');
        }

        return redirect()->back();
    }


    /**
     * Delete record after retrieving from the request
     *
     * @param Module $module
     * @param Request $request
     * @return void
     */
    protected function savePosition(?Domain $domain, Module $module, Request $request)
    {
        dd("{save in dataBase......}");
    }
}
