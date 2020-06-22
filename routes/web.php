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

    Route::get('connect/reload', 'ConnectController@redirectToProvider')->name('connect.reload');
    Route::get('connect/facebook', 'ConnectController@redirectToProvider')->name('facebook.login');
    Route::get('connect/facebook/callback', 'ConnectController@handleProviderCallback');
    Route::get('/posts/{page_id}', 'ConnectController@getPosts')->name('page.posts');
    Route::get('/connect', 'ConnectController@index')->name('connect.index');

    Route::get('/home', 'AccountController@index')->name('home');
    Route::get('/account', 'AccountController@index')->name('account.index');

    Route::get('/posts', 'PostsController@index')->name('posts.index');
    Route::post('/posts', 'PostsController@store')->name('posts.store');
    Route::delete('/posts/{post}', 'PostsController@destroy')->name('posts.destroy');

    Route::patch('/pages', 'PagesController@update')->name('pages.update');
});
