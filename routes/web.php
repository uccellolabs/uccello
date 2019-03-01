<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::name('uccello.')->group(function() {

    // Adapt params if we use or not multi domains
    if (!uccello()->useMultiDomains()) {
        $domainParam = '';
        $domainAndModuleParams = '{module}';
    } else {
        $domainParam = '{domain}';
        $domainAndModuleParams = '{domain}/{module}';
    }

    // Overrided routes
    Route::get($domainParam.'/api/doc', 'Core\SwaggerController@api')
        ->name('api.doc');

    // Overrided routes
    Route::get($domainParam.'/role/edit', 'Role\EditController@process')
        ->defaults('module', 'role')
        ->name('role.edit');

    Route::get($domainParam.'/user/edit', 'User\EditController@process')
        ->defaults('module', 'user')
        ->name('user.edit');

    Route::post($domainParam.'/domain', 'Domain\EditController@save')
        ->defaults('module', 'domain')
        ->name('domain.save');

    // Settings
    Route::get($domainParam.'/settings', 'Settings\SettingsController@dashboard')
        ->defaults('module', 'settings')
        ->name('settings.dashboard');

    Route::get($domainParam.'/settings/module/manager', 'Settings\ModuleManagerController@process')
        ->defaults('module', 'settings')
        ->name('settings.module.manager');

    Route::post($domainParam.'/settings/module/activation', 'Settings\ModuleManagerController@activation')
        ->defaults('module', 'settings')
        ->name('settings.module.activation');

    Route::get($domainParam.'/settings/menu/manager', 'Settings\MenuManagerController@process')
        ->defaults('module', 'settings')
        ->name('settings.menu.manager');

    Route::post($domainParam.'/settings/menu/store', 'Settings\MenuManagerController@store')
        ->defaults('module', 'settings')
        ->name('settings.menu.store');

    Route::post($domainParam.'/settings/menu/reset', 'Settings\MenuManagerController@reset')
        ->defaults('module', 'settings')
        ->name('settings.menu.reset');


    // Default routes
    Route::get($domainParam.'/home', 'Core\IndexController@process')
        ->defaults('module', 'home')
        ->name('home');

    Route::get($domainAndModuleParams, 'Core\IndexController@process')->name('index');
    Route::get($domainAndModuleParams.'/list', 'Core\ListController@process')->name('list');
    Route::get($domainAndModuleParams.'/list/datatable/config', 'Core\ListController@processForDatatableConfig')->name('datatable.config');
    Route::post($domainAndModuleParams.'/list/datatable', 'Core\ListController@processForDatatable')->name('datatable');
    Route::post($domainAndModuleParams.'/list/autocomplete', 'Core\ListController@processForAutocomplete')->name('autocomplete');
    Route::post($domainAndModuleParams.'/list/filter', 'Core\ListController@saveFilter')->name('list.filter.save');
    Route::post($domainAndModuleParams.'/export', 'Core\ExportController@process')->name('export');
    Route::get($domainAndModuleParams.'/list/filter/delete', 'Core\ListController@deleteFilter')->name('list.filter.delete');
    Route::get($domainAndModuleParams.'/detail', 'Core\DetailController@process')->name('detail');
    Route::get($domainAndModuleParams.'/edit', 'Core\EditController@process')->name('edit');
    Route::get($domainAndModuleParams.'/edit/relation', 'Core\EditController@addRelation')->name('edit.relation.add');
    Route::get($domainAndModuleParams.'/delete', 'Core\DeleteController@process')->name('delete');
    Route::post($domainAndModuleParams, 'Core\EditController@save')->name('save');
});