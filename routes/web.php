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

Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout']);
Route::post('/payment', [App\Http\Controllers\OrderController::class, 'payment']);


Route::get('/', function () {
    return view('welcome');
});
