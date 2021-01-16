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

Route::prefix('pengaturan')->group(function() {
    Route::get('/', 'PengaturanController@index');
});

Route::prefix('user')->group(function() {
	Route::get('/', 'UserController@index');
	// Route::match(['get', 'post'],'/get-data','UserController@getData');
    Route::match(['get', 'post'],'/get-data','UserController@getData');
	Route::get('/create',['as' => 'user.create', 'uses' => 'UserController@create']);
	Route::match(['get','post'],'/store',['as' => 'user.store', 'uses' => 'UserController@store']);
	Route::match(['get','post','put'],'/update/{id}',['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::match(['get','post'],'/send-data','UserController@sendData');
	Route::match(['get', 'post'],'/check-username','UserController@checkUsername');
	Route::match(['get', 'post'],'/check-email','UserController@checkEmail');
	Route::match(['get', 'post'],'/{id}/reset','UserController@reset');
	Route::match(['get', 'post'],'/{id}/nonaktifkan','UserController@nonaktifkan');
	Route::match(['get', 'post'],'/{id}/aktifkan','UserController@aktifkan');
	Route::match(['get', 'post'],'/{id}/edit','UserController@edit');
	Route::get('/{id}/delete', 'UserController@destroy');
});

Route::prefix('permissions')->group(function() {
    Route::get('/', 'PermissionController@index');
    Route::match(['get', 'post'],'/get-data','PermissionController@getData');
    Route::get('/create',['as' => 'permissions.create', 'uses' => 'PermissionController@create']);
    Route::match(['get','post'],'/store',['as' => 'permissions.store', 'uses' => 'PermissionController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'permissions.update', 'uses' => 'PermissionController@update']);
    Route::match(['get', 'post'],'/{id}/edit','PermissionController@edit');
    Route::match(['get','post'],'/send-data','PermissionController@sendData');
    Route::get('/{id}/delete', 'PermissionController@destroy');
});

Route::prefix('roles')->group(function() {
    Route::get('/', 'RoleController@index');
    Route::match(['get', 'post'],'/get-data','RoleController@getData');
    Route::get('/create',['as' => 'roles.create', 'uses' => 'RoleController@create']);
    Route::match(['get','post'],'/store',['as' => 'roles.store', 'uses' => 'RoleController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'roles.update', 'uses' => 'RoleController@update']);
    Route::match(['get', 'post'],'/{id}/edit','RoleController@edit');
    Route::get('/{id}/delete', 'RoleController@destroy');
});

Route::prefix('supplier')->group(function() {
    Route::get('/', 'SupplierController@index');
    Route::match(['get', 'post'],'/get-data','SupplierController@getData');
    Route::get('/create',['as' => 'supplier.create', 'uses' => 'SupplierController@create']);
    Route::match(['get','post'],'/store',['as' => 'supplier.store', 'uses' => 'SupplierController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'supplier.update', 'uses' => 'SupplierController@update']);
    Route::match(['get', 'post'],'/{id}/edit','SupplierController@edit');
    Route::match(['get','post'],'/send-data','SupplierController@sendData');
    Route::get('/{id}/delete', 'SupplierController@destroy');
});