<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::name('uccello.')
->middleware('web', 'auth')
->namespace('Uccello\Core\Http\Controllers')
->group(function () {
    // Adapt params if we use or not multi domains
    if (!uccello()->useMultiDomains()) {
        $domainParam = '';
        $domainAndModuleParams = '{module}';
    } else {
        $domainParam = '{domain}';
        $domainAndModuleParams = '{domain}/{module}';
    }

    Route::get($domainParam.'/domains/tree/root', 'Core\DomainsTreeController@root')->name('domains.tree.root');
    Route::get($domainParam.'/domains/tree/children', 'Core\DomainsTreeController@children')->name('domains.tree.children');

    // Overrided routes
    Route::get($domainParam.'/role/edit', 'Role\EditController@process')
        ->defaults('module', 'role')
        ->name('role.edit');

    Route::get($domainParam.'/user/list', 'User\ListController@process')
        ->defaults('module', 'user')
        ->name('user.list');

    Route::get($domainParam.'/user/detail', 'User\DetailController@process')
        ->defaults('module', 'user')
        ->name('user.detail');

    Route::get($domainParam.'/user/edit', 'User\EditController@process')
        ->defaults('module', 'user')
        ->name('user.edit');

    Route::post($domainParam.'/user/privileges/import', 'User\ListController@import')
        ->defaults('module', 'user')
        ->name('user.privileges.import');

    Route::post($domainParam.'/user/password', 'User\AccountController@updatePasswordByAdmin')
        ->defaults('module', 'user')
        ->name('user.password.update_by_admin');

    Route::post($domainParam.'/domain', 'Domain\EditController@save')
        ->defaults('module', 'domain')
        ->name('domain.save');

    Route::post($domainParam.'/group/edit/relation', 'Group\EditController@addRelation')
        ->defaults('module', 'group')
        ->name('group.edit.relation.add');

    Route::get($domainParam.'/group/edit/relation/delete', 'Core\EditController@deleteRelation')
        ->defaults('module', 'group')
        ->name('group.edit.relation.delete');

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

    Route::get($domainParam.'/user-account', 'User\AccountController@index')
        ->defaults('module', 'home')
        ->name('user.account');

    Route::post($domainParam.'/user-account/profile', 'User\AccountController@updateProfile')
        ->defaults('module', 'home')
        ->name('user.profile.update');

    Route::post($domainParam.'/user-account/avatar', 'User\AccountController@updateAvatar')
        ->defaults('module', 'home')
        ->name('user.avatar.update');

    Route::post($domainParam.'/user-account/password', 'User\AccountController@updatePassword')
        ->defaults('module', 'home')
        ->name('user.password.update');

    Route::post($domainParam.'/user-account/settings', 'User\AccountController@updateSettings')
        ->defaults('module', 'home')
        ->name('user.settings.update');


    // Default routes
    Route::get($domainParam.'/home', 'Core\IndexController@process')
        ->defaults('module', 'home')
        ->name('home');

    Route::get($domainParam.'/search', 'Core\SearchController@search')->name('search');

    Route::get($domainAndModuleParams, 'Core\IndexController@process')->name('index');
    Route::get($domainAndModuleParams.'/list', 'Core\ListController@process')->name('list');
    Route::post($domainAndModuleParams.'/list/content', 'Core\ListController@processForContent')->name('list.content');
    Route::get($domainAndModuleParams.'/list/autocomplete', 'Core\ListController@processForAutocomplete')->name('autocomplete');
    Route::post($domainAndModuleParams.'/list/filter', 'Core\ListController@saveFilter')->name('list.filter.save');
    Route::post($domainAndModuleParams.'/list/filter/delete', 'Core\ListController@deleteFilter')->name('list.filter.delete');
    Route::post($domainAndModuleParams.'/export', 'Core\ExportController@process')->name('export');
    Route::get($domainAndModuleParams.'/detail', 'Core\DetailController@process')->name('detail');
    Route::get($domainAndModuleParams.'/detail/record/{id}', 'Core\DetailController@getRecord')->name('detail.record');
    Route::get($domainAndModuleParams.'/edit', 'Core\EditController@process')->name('edit');
    Route::post($domainAndModuleParams.'/edit/relation', 'Core\EditController@addRelation')->name('edit.relation.add');
    Route::get($domainAndModuleParams.'/edit/relation/delete', 'Core\EditController@deleteRelation')->name('edit.relation.delete');
    Route::get($domainAndModuleParams.'/popup_edit', 'Core\PopupEditController@process')->name('popup.edit');
    Route::post($domainAndModuleParams.'/popup', 'Core\PopupEditController@save')->name('popup.save');
    Route::get($domainAndModuleParams.'/delete', 'Core\DeleteController@process')->name('delete');
    Route::get($domainAndModuleParams.'/restore', 'Core\EditController@restore')->name('restore');
    Route::post($domainAndModuleParams, 'Core\EditController@save')->name('save');
    Route::get($domainAndModuleParams.'/download', 'Core\DownloadController@process')->name('download');
    Route::get($domainAndModuleParams.'/tree', 'Core\TreeController@process')->name('tree');
    Route::get($domainAndModuleParams.'/tree/root', 'Core\TreeController@root')->name('tree.root');
    Route::get($domainAndModuleParams.'/tree/children', 'Core\TreeController@children')->name('tree.children');

    Route::get($domainAndModuleParams.'/savePosition', 'Core\SummaryController@savePosition')->name('savePosition');
});
