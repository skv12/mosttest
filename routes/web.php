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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(App\Http\Controllers\ChatController::class)->group(function (){
    Route::get('/chats', 'index');
    Route::post('/chats', 'addChat')->name('addChat');
    Route::get('/chats/{id}', 'chat');
    Route::post('/chats/{id}/message', 'message');
});

Route::controller(App\Http\Controllers\UserController::class)->group(function (){
    Route::get('/users', 'index');
    Route::get('/user/{id}', 'chats');
});
Route::controller(App\Http\Controllers\MessageController::class)->group(function (){
    Route::post('/message', 'setMessageSeen')->name('setMessageSeen');
});
