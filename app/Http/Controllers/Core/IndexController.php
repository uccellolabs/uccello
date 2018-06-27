<?php

namespace Uccello\Core\Http\Controllers\Core;

class IndexController extends Controller
{
    protected $viewName = 'index.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:retrieve');
    }
}
