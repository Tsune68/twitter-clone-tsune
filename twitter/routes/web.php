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
    Route::get('/users', [App\Http\Controllers\UserController::class, 'showAllUsers'])->name('users.index');
    Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'findByUserId'])->name('users.show');
    Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');
    Route::post('/users/follow/{id}', [App\Http\Controllers\UserController::class, 'follow'])->name('users.follow');
    Route::post('/users/unfollow/{id}', [App\Http\Controllers\UserController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/followers/{id}', [App\Http\Controllers\UserController::class, 'showAllfollowers'])->name('users.followers');
    Route::get('/users/follows/{id}', [App\Http\Controllers\UserController::class, 'showAllfollows'])->name('users.follows');

    Route::get('/tweets/create', [App\Http\Controllers\TweetController::class, 'create'])->name('tweets.create');
    Route::post('/tweets', [App\Http\Controllers\TweetController::class, 'store'])->name('tweets.store');
    Route::get('/tweets', [App\Http\Controllers\TweetController::class, 'index'])->name('tweets.index');
    Route::get('/tweets/{id}', [App\Http\Controllers\TweetController::class, 'findByTweetId'])->name('tweets.show');
    Route::post('/tweets/{id}', [App\Http\Controllers\TweetController::class, 'update'])->name('tweets.update');
    Route::delete('/tweets/{id}', [App\Http\Controllers\TweetController::class, 'delete'])->name('tweets.delete');
});
