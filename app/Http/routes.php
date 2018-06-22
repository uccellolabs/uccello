<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::name('uccello.')->group(function () {

    // Overrided routes
    Route::get('{domain}/role/edit', 'Role\EditController@process')
        ->defaults('module', 'role')
        ->name('role.edit');

    // Default routes
    Route::get('{domain}/{module}', 'Core\IndexController@process')->name('index');
    Route::get('{domain}/{module}/list', 'Core\ListController@process')->name('list');
    Route::post('api/{domain}/{module}/list', 'Core\ApiController@index')->name('api');
    Route::get('{domain}/{module}/detail', 'Core\DetailController@process')->name('detail');
    Route::get('{domain}/{module}/edit', 'Core\EditController@process')->name('edit');
    Route::get('{domain}/{module}/delete', 'Core\DeleteController@process')->name('delete');
    Route::post('{domain}/{module}', 'Core\EditController@store')->name('store');
});