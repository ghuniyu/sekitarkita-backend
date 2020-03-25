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
    return redirect('/landing');
});

Route::get('/download', function () {
    return redirect('https://github.com/ghuniyu/sekitarkita-mobile/releases/download/1.0.2/sekitarkita.apk');
});

Route::view('/mapping', 'mapping.device')->name('mapping.device');
Route::view('/mapping-member', 'mapping.member')->name('mapping.member');
Route::view('/mapping-puppeteer', 'mapping.mapping-for-puppeteer');
