<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:employees_control'], 'namespace' => 'App\Http\Controllers\Admin\Employee'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'employees', 'as' => 'employees.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'EmployeeController@index'
            ]);
            Route::put('{employee}/update', [
                'as' => 'update',
                'uses' => 'EmployeeController@update'
            ]);
        });
        Route::group(['prefix' => 'employees-ajax', 'as' => 'employees.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'EmployeeAjaxController@getDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'EmployeeAjaxController@show'
            ]);
            Route::post('/active', [
                'as' => 'active',
                'uses' => 'EmployeeAjaxController@active'
            ]);
        });
    });
});
