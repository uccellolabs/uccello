<?php

namespace Sardoj\Uccello\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Menu\Laravel\Facades\Menu;
use Spatie\Menu\Html;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function process($domain, $module)
    {
        // Get user
        $user = Auth::user();

        return view('uccello::list.main', compact('domain', 'module', 'user'));
    }
}
