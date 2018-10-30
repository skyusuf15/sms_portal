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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'dashboard', 'namespace' => 'Admin' , 'middleware' => ['auth']],function(){
    // Route::get('/', 'HomeController@index')->name('home');

    Route::get('/wallet-balance', 'HomeController@getWalletBalance');

    Route::group(['prefix' => 'sms'], function(){
        // Route::get('/', 'MessageController@index');

        Route::get('/bulk', 'MessageController@showSendBulk');
        Route::post('/bulk', 'MessageController@sendBulk');

        Route::get('/quick', 'MessageController@showSendQuick');
        Route::post('/quick', 'MessageController@sendQuick');

        Route::get('/data', 'MessageController@getSMSData');

        Route::get('/batch/{batch_no}', 'MessageController@showSMSBatch');
        Route::get('/message/{message_id}', 'MessageController@showSMS');
    
    });
});
