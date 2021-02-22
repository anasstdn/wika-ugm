<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    // return view('welcome');
    if (Auth::check()) {
        return redirect('home');
    } else {
        return redirect('login');
    }
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::match(['get', 'post'],'home/material-dropdown','HomeController@materialDropdown');
Route::match(['get', 'post'],'home/get-data-stok','HomeController@getDataStok');
Route::match(['get', 'post'],'home/get-data-riwayat-stok','HomeController@getDataRiwayatStok');

Route::prefix('activity-log')->group(function() {
    Route::get('/', 'ActivityLogController@index');
    Route::match(['get', 'post'],'/get-data','ActivityLogController@getData');
    Route::match(['get', 'post'],'/users','ActivityLogController@loadUsers');
});

Route::group(['middleware' => ['auth']], function() {

});