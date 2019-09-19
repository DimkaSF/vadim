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

Route::get('/', 'MyController@home');

Route::get('/album_{id}', 'MyController@index_album');
Route::get('/login', 'LoginController@showLogin')->name('showLogin');
Route::post('/login', 'LoginController@doLogin')->name('doLogin');
Route::get('/logout', 'LoginController@LogOut');

Route::group(['prefix'=>"admin", 'middleware'=>"auth"], function(){
    Route::get('/', 'AdminController@index')->middleware('auth')->name('admin');
    Route::post('/send_photo', 'AdminController@save_photo');
    Route::get('/delete_al/show_photo_{id}', 'AdminController@show_preview');
    Route::get("/delete_al_{id}", 'AdminController@delete_album');
    Route::get("/edit_al_{id}", 'AdminController@show_preview');
    Route::get("/delete/album_{al_id}/photo_{ph_id}", 'AdminController@delete_one_photo');
    Route::post("/edit_al/new_cover", 'AdminController@save_new_cover');
    Route::post("/gettags", 'AdminController@getTags');
    Route::post("/save_new_tags", 'AdminController@saveNewTags');
});

Route::get('/home', 'MyController@home')->name('home');
Route::get('/me', 'MyController@WhoAmI');
