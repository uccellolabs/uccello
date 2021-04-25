<?php

use Illuminate\Support\Facades\Route;
use Uccello\Core\Facades\Uccello;

Route::name('uccello.')
// ->middleware('web', 'auth')
->middleware('web')
->namespace(\Uccello\Core\Http\Controllers::class)
->group(function () {
    // Adapt params if we use or not multi domains
    if (!Uccello::useMultiDomains()) {
        $domainParam = '';
        $domainAndModuleParams = '{module}';
    } else {
        $domainParam = '{domain}';
        $domainAndModuleParams = '{domain}/{module}';
    }

    Route::get($domainAndModuleParams, 'Core\ListController')->name('list');
    Route::get($domainAndModuleParams.'/{id}', 'Core\DetailController')->where('id', '[0-9]+')->name('detail');
});
