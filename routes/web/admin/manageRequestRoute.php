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
//            Route::get('/getRoles', [
//                'as' => 'getRoles',
//                'uses' => 'ManageRequestAjaxController@getRoles'
//            ]);
//            Route::post('/getUserWithRole', [
//                'as' => 'getUserWithRole',
//                'uses' => 'ManageRequestAjaxController@getUserWithRole'
//            ]);
//            Route::post('/store', [
//                'as' => 'store',
//                'uses' => 'ManageRequestAjaxController@store'
//            ]);
        });
    });
});
