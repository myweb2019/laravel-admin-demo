<?php
// Route::group(['prefix' => 'login'], function() {

// });

Route::get('getcode', 'Login\LoginController@getcode');
Route::post('login', 'Login\LoginController@loginApi');


Route::group(['middleware' => 'TokenAuth'], function () {
    Route::get('userlist', 'Login\LoginController@getUserlist');
});
