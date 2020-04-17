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
    return redirect('https://landing.sekitarkita.id');
});

Route::get('/download', function () {
    return redirect('https://play.google.com/store/apps/details?id=com.linkensky.ornet');
});

Route::view('/mapping', 'mapping.device')->name('mapping.device');
Route::view('/mapping-member', 'mapping.member')->name('mapping.member')
    ->middleware('partner');
Route::view('/mapping-puppeteer', 'mapping.mapping-for-puppeteer');

Route::view('/tracking', 'tracking.index')->name('tracking.view')
    ->middleware(config('nova.middleware', []));
Route::view('/filteredTrack', 'tracking.filtered')->name('tracking.filtered')
    ->middleware(config('nova.middleware', []));

Route::get('api/track/{device}', 'Api\DeviceController@track')
    ->middleware(config('nova.middleware', []));
Route::get('api/member-interaction', 'Api\MappingController@associatedInteraction')
    ->middleware('partner');
Route::get('api/device-interaction', 'Api\MappingController@recordedInteraction')
    ->middleware('partner');
Route::get('api/filteredTrack/', 'Api\DeviceController@filteredTracking')
    ->middleware(config('nova.middleware', []));

