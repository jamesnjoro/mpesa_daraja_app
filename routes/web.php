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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::middleware(['auth'])->group(function () {
   Route::post('/getAuthToken','TransactionController@getAuthenticationToken');
   Route::post('/registerURL','TransactionController@registerUrl');
});

Route::any('/validateTrans', 'TransactionController@validationUrl')->name('validationURL');
Route::any('/confirmTrans', 'TransactionController@confirmationUrl')->name('confirmationURL');
