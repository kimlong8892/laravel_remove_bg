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

Route::get('/remove-background', [\App\Http\Controllers\RemoveBackgroundController::class, 'Index']);

Route::post('/remove-background', [\App\Http\Controllers\RemoveBackgroundController::class, 'RemoveBackground'])->name('remove_background.post');
