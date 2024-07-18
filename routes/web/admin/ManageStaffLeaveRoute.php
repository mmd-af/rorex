<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage_leaves'], 'namespace' => 'App\Http\Controllers\Admin\ManageStaffLeave'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'manageStaffLeaves', 'as' => 'manageStaffLeaves.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ManageStaffLeaveController@index'
            ]);
            Route::put('{employee}/update', [
                'as' => 'update',
                'uses' => 'ManageStaffLeaveController@update'
            ]);
        });
        Route::group(['prefix' => 'manageStaffLeaves-ajax', 'as' => 'manageStaffLeaves.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'ManageStaffLeaveAjaxController@getDataTable'
            ]);
        });
    });
});
