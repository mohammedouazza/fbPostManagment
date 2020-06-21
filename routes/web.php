<?php

use Illuminate\Support\Facades\Route;

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



Route::middleware(['auth'])->group(function () {

    Route::get('connect/facebook', 'ConnectController@redirectToProvider')->name('facebook.login');
    Route::get('connect/facebook/callback', 'ConnectController@handleProviderCallback');
    Route::post('/pages/facebook', 'GraphController@publishToPage')->name('page.publish');


    Route::get('/user', 'GraphController@retrieveUserProfile')->name('user.profile');

    Route::post('/user', 'GraphController@publishToProfile')->name('user.publish');

    Route::get('/pages/facebook', 'GraphController@retrieveUserPages')->name('user.pages');
    Route::get('/pages/facebook/{page_id}', 'GraphController@single_page')->name('pages.show');


    Route::get('/home', 'AccountController@index')->name('home');
    Route::get('/account', 'AccountController@index')->name('account.index');
    Route::get('/connect', 'ConnectController@index')->name('connect.index');

    Route::get('/posts', 'PostsController@index')->name('posts.index');
    Route::post('/posts', 'PostsController@store')->name('posts.store');
    Route::delete('/posts/{post}', 'PostsController@destroy')->name('posts.destroy');

    Route::patch('/pages', 'PagesController@update')->name('pages.update');
});
