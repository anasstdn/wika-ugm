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

Route::prefix('material')->group(function() {
    Route::get('/', 'MaterialController@index');
    Route::match(['get', 'post'],'/get-data','MaterialController@getData');
    Route::get('/create',['as' => 'material.create', 'uses' => 'MaterialController@create']);
    Route::get('/{id}/add-child', 'MaterialController@addChild');
    Route::get('/{id}/edit-child', 'MaterialController@editChild');
    Route::match(['get','post'],'/material-search','MaterialController@materialSearch');
    Route::match(['get','post'],'/store',['as' => 'material.store', 'uses' => 'MaterialController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'material.update', 'uses' => 'MaterialController@update']);
    Route::match(['get', 'post'],'/{id}/edit','MaterialController@edit');
    Route::match(['get','post'],'/send-data','MaterialController@sendData');
    Route::get('/{id}/delete', 'MaterialController@destroy');
});

Route::prefix('departement')->group(function() {
    Route::get('/', 'DepartementController@index');
    Route::match(['get', 'post'],'/get-data','DepartementController@getData');
    Route::get('/create',['as' => 'departement.create', 'uses' => 'DepartementController@create']);
    Route::match(['get','post'],'/store',['as' => 'departement.store', 'uses' => 'DepartementController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'departement.update', 'uses' => 'DepartementController@update']);
    Route::match(['get', 'post'],'/{id}/edit','DepartementController@edit');
    Route::match(['get','post'],'/send-data','DepartementController@sendData');
    Route::get('/{id}/delete', 'DepartementController@destroy');
});

Route::prefix('jabatan')->group(function() {
    Route::get('/', 'JabatanController@index');
    Route::match(['get', 'post'],'/get-data','JabatanController@getData');
    Route::get('/create',['as' => 'jabatan.create', 'uses' => 'JabatanController@create']);
    Route::match(['get','post'],'/store',['as' => 'jabatan.store', 'uses' => 'JabatanController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'jabatan.update', 'uses' => 'JabatanController@update']);
    Route::match(['get', 'post'],'/{id}/edit','JabatanController@edit');
    Route::match(['get','post'],'/send-data','JabatanController@sendData');
    Route::get('/{id}/delete', 'JabatanController@destroy');
});

Route::prefix('agama')->group(function() {
    Route::get('/', 'AgamaController@index');
    Route::match(['get', 'post'],'/get-data','AgamaController@getData');
    Route::get('/create',['as' => 'agama.create', 'uses' => 'AgamaController@create']);
    Route::match(['get','post'],'/store',['as' => 'agama.store', 'uses' => 'AgamaController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'agama.update', 'uses' => 'AgamaController@update']);
    Route::match(['get', 'post'],'/{id}/edit','AgamaController@edit');
    Route::match(['get','post'],'/send-data','AgamaController@sendData');
    Route::get('/{id}/delete', 'AgamaController@destroy');
});

Route::prefix('jenis-kelamin')->group(function() {
    Route::get('/', 'JenisKelaminController@index');
    Route::match(['get', 'post'],'/get-data','JenisKelaminController@getData');
    Route::get('/create',['as' => 'jenis-kelamin.create', 'uses' => 'JenisKelaminController@create']);
    Route::match(['get','post'],'/store',['as' => 'jenis-kelamin.store', 'uses' => 'JenisKelaminController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'jenis-kelamin.update', 'uses' => 'JenisKelaminController@update']);
    Route::match(['get', 'post'],'/{id}/edit','JenisKelaminController@edit');
    Route::match(['get','post'],'/send-data','JenisKelaminController@sendData');
    Route::get('/{id}/delete', 'JenisKelaminController@destroy');
});

Route::prefix('status-perkawinan')->group(function() {
    Route::get('/', 'StatusPerkawinanController@index');
    Route::match(['get', 'post'],'/get-data','StatusPerkawinanController@getData');
    Route::get('/create',['as' => 'status-perkawinan.create', 'uses' => 'StatusPerkawinanController@create']);
    Route::match(['get','post'],'/store',['as' => 'status-perkawinan.store', 'uses' => 'StatusPerkawinanController@store']);
    Route::match(['get','post','put'],'/update/{id}',['as' => 'status-perkawinan.update', 'uses' => 'StatusPerkawinanController@update']);
    Route::match(['get', 'post'],'/{id}/edit','StatusPerkawinanController@edit');
    Route::match(['get','post'],'/send-data','StatusPerkawinanController@sendData');
    Route::get('/{id}/delete', 'StatusPerkawinanController@destroy');
});

Route::prefix('profil')->group(function() {
    Route::get('/', 'ProfilController@index');
    Route::get('/edit/{id}', 'ProfilController@edit');
    Route::match(['get','post'],'/send-data','ProfilController@sendData');
    Route::match(['get','post','put'],'/update/{id}',['as' => 'profil.update', 'uses' => 'ProfilController@update']);
    Route::match(['get','post'],'/load-data-jenis-kelamin','ProfilController@loadDataJenisKelamin');
    Route::match(['get','post'],'/load-data-agama','ProfilController@loadDataAgama');
    Route::match(['get','post'],'/load-data-status-perkawinan','ProfilController@loadDataStatusPerkawinan');
});