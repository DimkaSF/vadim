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

Route::group(['prefix'=>"admin", 'middleware'=>"auth"], function(){
    Route::get('/', 'AdminController@index')->middleware('auth')->name('admin');
    Route::post('/send_photo', 'AdminController@save_photo');
    Route::get('/delete_al/show_photo_{id}', 'AdminController@show_preview');
    Route::get("/delete_al_{id}", 'AdminController@delete_album');
    Route::get("/edit_al_{id}", 'AdminController@showPreview');
    Route::post("/deletephoto", 'AdminController@deleteOnePhoto');
    Route::post("/edit_al/new_cover", 'AdminController@save_new_cover');
    Route::get("/getalbmsnames", 'AdminController@getAlbumsNames');
    Route::post("/getalbmsphotos", 'AdminController@getAlbumsPhotos');
    Route::post("/gettags", 'AdminController@getTags');
    Route::post("/save_new_tags", 'AdminController@saveNewTags');
    Route::post("/savephoto", 'AdminController@savePhoto');
    Route::post("/createalbum", "AdminController@createAlbum");
    Route::post("/saveafteredit", "AdminController@saveAfterEdit");
    Route::post("/savenewcover", "AdminController@saveNewCover");
});

Route::get('/genres', 'MyController@getGenresIndex');
Route::get('/genres/gettags', 'MyController@getTags');
Route::get('/genres/{tag}', 'MyController@getAlbumsWithTag');
Route::get('/home', 'MyController@home')->name('home');
Route::get('/me', 'MyController@WhoAmI');
Route::get('/login', 'LoginController@showLogin')->name('showLogin');
Route::post('/login', 'LoginController@doLogin')->name('doLogin');
Route::get('/logout', 'LoginController@LogOut');

/*Этот путь последний, который отрисовывает альбомы*/
Route::get('/album/{slug}', 'MyController@index_album_desk');
