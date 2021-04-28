<?php

use Illuminate\Support\Facades\Route;
use Uccello\Core\Facades\Uccello;

Route::name('uccello.')
// ->middleware('web', 'auth')
->middleware('web')
->namespace(\Uccello\Core\Http\Controllers::class)
->group(function () {
    // Adapt params if we use or not multi workspace
    if (!Uccello::useMultiWorkspaces()) {
        $workspaceParam = '';
        $workspaceAndModuleParams = '{module}';
    } else {
        $workspaceParam = '{workspace}';
        $workspaceAndModuleParams = '{workspace}/{module}';
    }

    Route::get($workspaceAndModuleParams, 'Core\ListController')->name('list');
    Route::get($workspaceAndModuleParams.'/{id}', 'Core\DetailController')->where('id', '[0-9]+')->name('detail');
});
