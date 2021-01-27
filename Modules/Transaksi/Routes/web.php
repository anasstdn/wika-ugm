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
    Route::match(['get', 'post'],'/get-data-diterima','SPMController@getDataDiterima');
    Route::match(['get', 'post'],'/get-data-ditolak','SPMController@getDataDitolak');
    Route::get('/create',['as' => 'spm.create', 'uses' => 'SPMController@create']);
    Route::match(['get', 'post'],'/material-search','SPMController@searchMaterial');
    Route::match(['get','post'],'/store',['as' => 'spm.store', 'uses' => 'SPMController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'spm.update', 'uses' => 'SPMController@update']);
    Route::match(['get', 'post'],'/{id}/edit','SPMController@edit');
    Route::match(['get', 'post'],'/{id}/view','SPMController@show');
    Route::match(['get','post'],'/send-data','SPMController@sendData');
    Route::match(['get','post'],'/load-data-material','SPMController@loadDataMaterial');
    Route::get('/delete-detail-spm', 'SPMController@deleteDetailSpm');
    Route::get('/{id}/batal', 'SPMController@batal');
});

Route::prefix('verifikasi-spm')->group(function() {
    Route::get('/', 'VerifikasiSPMController@index');
    Route::match(['get', 'post'],'/get-data','VerifikasiSPMController@getData');
    Route::match(['get', 'post'],'/get-data-diterima','VerifikasiSPMController@getDataDiterima');
    Route::match(['get', 'post'],'/get-data-ditolak','VerifikasiSPMController@getDataDitolak');
    Route::get('/create',['as' => 'verifikasi-spm.create', 'uses' => 'VerifikasiSPMController@create']);
    Route::match(['get', 'post'],'/material-search','VerifikasiSPMController@searchMaterial');
    Route::match(['get','post'],'/store',['as' => 'verifikasi-spm.store', 'uses' => 'VerifikasiSPMController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'verifikasi-spm.update', 'uses' => 'VerifikasiSPMController@update']);
    Route::match(['get', 'post'],'/{id}/edit','VerifikasiSPMController@edit');
    Route::match(['get', 'post'],'/{id}/view','VerifikasiSPMController@show');
    Route::match(['get', 'post'],'/{id}/test-pdf','VerifikasiSPMController@test_pdf');
    Route::match(['get', 'post'],'/{id}/verifikasi','VerifikasiSPMController@verifikasi');
    Route::match(['get', 'post'],'/verifikasi-komersil','VerifikasiSPMController@verifikasiKomersil');
    Route::match(['get','post'],'/send-data','VerifikasiSPMController@sendData');
    Route::match(['get','post'],'/load-data-material','VerifikasiSPMController@loadDataMaterial');
    Route::get('/delete-detail-spm', 'VerifikasiSPMController@deleteDetailSpm');
    Route::get('/{id}/batal', 'VerifikasiSPMController@batal');
});
