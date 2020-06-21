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

Route::middleware('auth:api')->group(function () {

    Route::get('login/facebook', 'Auth\LoginController@redirectToProvider')->name('facebook.login');
    Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');

    Route::post('/page', 'GraphController@publishToPage')->name('page.publish');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/user', 'GraphController@retrieveUserProfile')->name('user.profile');

    Route::post('/user', 'GraphController@publishToProfile')->name('user.publish');

    Route::get('/pages', 'GraphController@retrieveUserPages')->name('user.pages');
});
