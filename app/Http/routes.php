<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::name('uccello.')->group(function () {

    Route::middleware('uccello.permissions:retrieve')->group(function () {
        Route::get('{domain}/{module}', 'IndexController@process')->name('index');
        Route::get('{domain}/{module}/list', 'ListController@process')->name('list');
        Route::post('api/{domain}/{module}/list', 'ApiController@index')->name('api');
        Route::get('{domain}/{module}/detail', 'DetailController@process')->name('detail');
    });

    Route::get('{domain}/{module}/edit', 'EditController@process')->middleware('uccello.permissions:create')->name('edit');
    Route::get('{domain}/{module}/delete', 'DeleteController@process')->middleware('uccello.permissions:delete')->name('delete');
    Route::post('{domain}/{module}', 'EditController@store')->middleware('uccello.permissions:create')->name('store');
});