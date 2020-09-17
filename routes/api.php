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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('blog', 'FrontController@blog');
Route::get('category/{category}', 'FrontController@category');
Route::get('checkAdmin', 'ServiceController@admin')->middleware('admin');
Route::post('contact', 'ServiceController@contact');
Route::get('getPagedCats/{category}/{page}', 'FrontController@getPagedCats');
Route::get('getPagedPosts/{page}', 'FrontController@getPagedPosts');
Route::get('getPagedTag/{tag}/{page}', 'FrontController@getPagedTag');
Route::post('getPass', 'ServiceController@getPass');
Route::get('index', 'FrontController@index');
Route::get('post/{slug}', 'FrontController@post');
Route::delete('removeComment/{commentId}', 'CommentController@removeComment')->middleware('admin');
Route::get('tag/{tag}', 'FrontController@tag');

Route::get('search/{search}/{page?}', 'FrontController@search');
Route::get('searchPostsOnly/{search}/{page?}', 'FrontController@searchPostsOnly');

Route::group(['middleware' => ['auth:api']], function (){
    Route::post('blog/{postId}/comments', 'CommentController@store');
    Route::post('blog/{postId}/like', 'ServiceController@like');
    Route::post('avatar', 'ServiceController@avatar');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['admin']], function (){
    Route::apiResource('category', 'CategoryController');
    Route::apiResource('post', 'PostController');
    Route::apiResource('project', 'ProjectController');
    Route::apiResource('skill', 'SkillController');
    Route::apiResource('training', 'TrainingController');
    Route::post('user/delAvatar', 'UserController@delAvatar');
    Route::apiResource('user', 'UserController');
    Route::post('uploadEditorImage', 'UploadController@uploadEditorImage');

});

Route::group(['prefix' => 'auth'], function (){
    Route::post('register', 'Auth\RegisterController@action');
    Route::post('login', 'Auth\LoginController@action');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('logout', 'Auth\LogoutController@action');
    Route::get('me', 'Auth\MeController@action');
});
