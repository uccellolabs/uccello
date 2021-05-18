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

    Route::get($workspaceParam.'/onboarding', function() {
        return view('uccello::modules.onboarding.main');
    });
    Route::get($workspaceParam.'/dashboard', function() {
        return view('uccello::modules.dashboard.main');
    });
    Route::get($workspaceParam.'/admin-settings', function() {
        return view('uccello::modules.admin-settings.main');
    });
    Route::get($workspaceParam.'/add-from-marketplace', function() {
        return view('uccello::modules.add-from-marketplace.main');
    });
    Route::get($workspaceParam.'/settings', function() {
        return view('uccello::modules.settings.main');
    });
    Route::get($workspaceAndModuleParams, 'Core\ListController')->name('list');
    Route::get($workspaceAndModuleParams.'/{id}', 'Core\DetailController')->where('id', '[0-9]+')->name('detail');
});
