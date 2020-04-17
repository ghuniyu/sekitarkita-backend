<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('throttle:4,1')
    ->post('store-device', 'Api\DeviceController@store');

Route::post('me', 'Api\DeviceController@getMe');
Route::post('store-firebase-token', 'Api\DeviceController@storeFirebaseToken');
Route::post('device-history', 'Api\DeviceController@getNearby');
Route::post('set-health', 'Api\DeviceController@setHealth');
Route::post('change-status', 'Api\DeviceController@changeRequest');

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Developer'
    ]);
});

Route::get('/call-centers', 'Api\InfoController@getCallCenters');
Route::get('/hospitals', 'Api\InfoController@getHospitals');
Route::get('/partners', 'Api\InfoController@getPartners');
Route::middleware('throttle:4,1')
    ->post('/partners', 'Api\InfoController@reportPartners');
Route::get('/track/{device}', 'Api\DeviceController@track')
    ->middleware(config('nova.middleware', []));
Route::get('/filteredTrack/', 'Api\DeviceController@filteredTracking')
    ->middleware(config('nova.middleware', []));
