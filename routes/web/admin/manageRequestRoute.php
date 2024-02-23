<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\ManageRequest'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'manageRequests', 'as' => 'manageRequests.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ManageRequestController@index'
            ]);
        });
        Route::group(['prefix' => 'manageRequests-ajax', 'as' => 'manageRequests.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'ManageRequestAjaxController@getDataTable'
            ]);
            Route::post('/sign', [
                'as' => 'sign',
                'uses' => 'ManageRequestAjaxController@sign'
            ]);
        });
    });
});
