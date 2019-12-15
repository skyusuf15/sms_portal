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
    return redirect('/login');
});

Auth::routes();

Route::group(['prefix' => 'dashboard', 'namespace' => 'Admin' , 'middleware' => ['auth']],function(){
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/wallet-balance', 'HomeController@getWalletBalance');
    Route::get('/getPrefixCount', 'HomeController@getTelcosCount');

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

    Route::group(['prefix' => 'sms_history'], function() {
        Route::get('/upload', 'MessageHistoryController@upload')->name('upload');   
        Route::post('/upload', 'MessageHistoryController@upload_file')->name('upload-file'); 

        Route::get('/show', 'MessageHistoryController@show')->name('show'); 
    });
});
