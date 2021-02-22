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

Route::prefix('survei')->group(function() {
    Route::get('/', 'SurveiController@index');
    Route::match(['get', 'post'],'/get-data','SurveiController@getData');
    Route::match(['get', 'post'],'/load-table','SurveiController@loadTable');
    Route::match(['get', 'post'],'/get-data-diterima','SurveiController@getDataDiterima');
    Route::get('/{id}/form-survei', 'SurveiController@formSurvei');
    Route::match(['get', 'post'],'/get-data-ditolak','SurveiController@getDataDitolak');
    Route::get('/create',['as' => 'survei.create', 'uses' => 'SurveiController@create']);
    Route::match(['get', 'post'],'/material-search','SurveiController@searchMaterial');
    Route::match(['get','post'],'/store',['as' => 'survei.store', 'uses' => 'SurveiController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'survei.update', 'uses' => 'SurveiController@update']);
    Route::match(['get', 'post'],'/{id}/edit','SurveiController@formSurvei');
    Route::match(['get', 'post'],'/{id}/view','SurveiController@show');
    Route::match(['get','post'],'/send-data','SurveiController@sendData');
    Route::match(['get','post'],'/load-data-material','SurveiController@loadDataMaterial');
    Route::get('/{id}/batal', 'SurveiController@batal');
});

Route::prefix('po')->group(function() {
    Route::get('/', 'PurchaseOrderController@index');
    Route::match(['get', 'post'],'/get-data','PurchaseOrderController@getData');
    Route::match(['get', 'post'],'/load-table','PurchaseOrderController@loadTable');
    Route::match(['get', 'post'],'/get-data-diterima','PurchaseOrderController@getDataDiterima');
    Route::get('/{id}/form-survei', 'PurchaseOrderController@formSurvei');
    Route::match(['get', 'post'],'/get-data-ditolak','PurchaseOrderController@getDataDitolak');
    Route::get('/create',['as' => 'po.create', 'uses' => 'PurchaseOrderController@create']);
    Route::match(['get', 'post'],'/material-search','PurchaseOrderController@searchMaterial');
    Route::match(['get','post'],'/store',['as' => 'po.store', 'uses' => 'PurchaseOrderController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'po.update', 'uses' => 'PurchaseOrderController@update']);
    Route::match(['get', 'post'],'/{id}/edit','PurchaseOrderController@formSurvei');
    Route::match(['get', 'post'],'/{id}/view','PurchaseOrderController@show');
    Route::match(['get', 'post'],'/{id}/buat-po','PurchaseOrderController@buatPO');
    Route::match(['get','post'],'/send-data','PurchaseOrderController@sendData');
    Route::match(['get','post'],'/load-data-material','PurchaseOrderController@loadDataMaterial');
    Route::get('/{id}/batal', 'PurchaseOrderController@batal');
});
