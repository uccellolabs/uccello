<?php

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::name('uccello.')->group(function () {
    Route::get('{domain}/{module}', 'IndexController@process')->name('index');
    Route::get('{domain}/{module}/list', 'ListController@process')->name('list');
    Route::post('api/{domain}/{module}/list', 'ApiController@index')->middleware('auth')->name('api');
    Route::get('{domain}/{module}/detail', 'DetailController@process')->name('detail');
    Route::get('{domain}/{module}/edit', 'EditController@process')->name('edit');
    Route::get('{domain}/{module}/delete', 'DeleteController@process')->name('delete');
    Route::post('{domain}/{module}', 'EditController@store')->name('store');
});