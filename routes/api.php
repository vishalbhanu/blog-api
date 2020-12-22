<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('signup','LoginController@signUp');
Route::post('login','LoginController@login');
Route::post('create-post','LoginController@createPost');
Route::post('update-post','LoginController@updatePost');
Route::post('delete-post','LoginController@deletePost');
Route::post('get-post','LoginController@getPost');
Route::post('get-posts-by-tag','LoginController@getPostsByTag');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
