<?php

Route::name('uccello.api.')->group(function() {

    // Adapt params if we use or not multi domains
    if (!uccello()->useMultiDomains()) {
        $domainAndModuleParams = '{module}';
    } else {
        $domainAndModuleParams = '{domain}/{module}';
    }

    Route::post('login', 'Core\ApiAuthController@login')->name('login');
    Route::get('logout', 'Core\ApiAuthController@logout')->name('logout');
    Route::get('me', 'Core\ApiAuthController@me')->name('me');
    Route::get('refresh', 'Core\ApiAuthController@refresh')->name('refresh');

    Route::get($domainAndModuleParams, 'Core\ApiController@index')->name('index');
    Route::get($domainAndModuleParams.'/{id}', 'Core\ApiController@show')->name('show');
    Route::post($domainAndModuleParams, 'Core\ApiController@store')->name('store');
    Route::post($domainAndModuleParams.'/{id}', 'Core\ApiController@update')->name('update');
    Route::delete($domainAndModuleParams.'/{id}', 'Core\ApiController@destroy')->name('destroy');
});
