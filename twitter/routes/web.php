<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'users', 'as' => 'users.'], function() {
        Route::get('/', [App\Http\Controllers\UserController::class, 'showAllUsers'])->name('index');
        Route::get('{id}', [App\Http\Controllers\UserController::class, 'findByUserId'])->name('show');
        Route::put('{id}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('delete');
        Route::post('follow/{id}', [App\Http\Controllers\UserController::class, 'follow'])->name('follow');
        Route::post('unfollow/{id}', [App\Http\Controllers\UserController::class, 'unfollow'])->name('unfollow');
        Route::get('followers/{id}', [App\Http\Controllers\UserController::class, 'showAllfollowers'])->name('followers');
        Route::get('follows/{id}', [App\Http\Controllers\UserController::class, 'showAllfollows'])->name('follows');    
    });

    Route::group(['prefix' => 'tweets', 'as' => 'tweets.'], function() {
        Route::post('/', [App\Http\Controllers\TweetController::class, 'store'])->name('store');
        Route::get('/', [App\Http\Controllers\TweetController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\TweetController::class, 'create'])->name('create');
        Route::get('{id}', [App\Http\Controllers\TweetController::class, 'findByTweetId'])->name('show');
        Route::post('{id}', [App\Http\Controllers\TweetController::class, 'update'])->name('update');
        Route::delete('{id}', [App\Http\Controllers\TweetController::class, 'delete'])->name('delete');
        Route::get('search', [App\Http\Controllers\TweetController::class, 'search'])->name('search');
        Route::post('favorite/{id}', [App\Http\Controllers\TweetController::class, 'favorite'])->name('favorite');
        Route::get('favorite/{id}', [App\Http\Controllers\TweetController::class, 'showAllFavoriteTweets'])->name('favorites'); 
        Route::post('reply/{id}', [App\Http\Controllers\TweetController::class, 'storeReply'])->name('reply');   
    });
});
