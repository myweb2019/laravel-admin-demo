<?php
Route::get('getcode', 'Login\LoginController@getcode');
Route::post('login', 'Login\LoginController@loginApi');


Route::group(['middleware' => 'TokenAuth'], function () {
    Route::post('userlist', 'IndexController@getUserlist');
});
