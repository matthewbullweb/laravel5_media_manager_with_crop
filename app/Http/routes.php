<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('admin', 'AdminController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::get('upload', 'UploadController@getUploads');
Route::get('photos', 'UploadController@getPhotos');
Route::get('videos', 'UploadController@getVideos');

/*Route::group(array('before' => 'auth'), function()
{
	Route::post('upload', 'UploadController@postUpload');
	Route::post('upload/delete', 'UploadController@postDelete');
});*/

// upload file
// Route::post('upload', function() {});

Route::post('upload', ['middleware' => 'auth', 'uses' => 'UploadController@postUpload']);
Route::post('upload/delete', ['middleware' => 'auth', 'uses' => 'UploadController@postDelete']);