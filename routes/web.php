<?php

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
})->name('index');

Route::get('/home', function () {
    return redirect()->route('index');
});

Route::resource('/product', 'ProductController');
Route::resource('/download', 'DownloadController')->only(['store', 'update', 'destroy']);

Auth::routes();