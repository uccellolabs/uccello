<?php

namespace Sardoj\Uccello\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Menu\Laravel\Facades\Menu;
use Spatie\Menu\Html;

class EditController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function process($domain, $module)
    {
        return view('uccello::edit.main');
    }
}
