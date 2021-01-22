<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('transaksi')->group(function() {
    Route::get('/', 'TransaksiController@index');
});

Route::prefix('spm')->group(function() {
    Route::get('/', 'SPMController@index');
    Route::match(['get', 'post'],'/get-data','SPMController@getData');
    Route::get('/create',['as' => 'spm.create', 'uses' => 'SPMController@create']);
    Route::match(['get', 'post'],'/material-search','SPMController@searchMaterial');
    Route::match(['get','post'],'/store',['as' => 'spm.store', 'uses' => 'SPMController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'spm.update', 'uses' => 'SPMController@update']);
    Route::match(['get', 'post'],'/{id}/edit','SPMController@edit');
    Route::match(['get', 'post'],'/{id}/view','SPMController@show');
    Route::match(['get','post'],'/send-data','SPMController@sendData');
    Route::get('/{id}/delete', 'SPMController@destroy');
});
