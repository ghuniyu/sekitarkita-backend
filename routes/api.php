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

Route::post('store-device', 'Api\DeviceController@store')
    ->middleware('throttle:4,1');

Route::post('me', 'Api\DeviceController@getMe');
Route::post('store-firebase-token', 'Api\DeviceController@storeFirebaseToken');
Route::post('store-selfcheck', 'Api\DeviceController@storeSelfCheck');
Route::post('v2/store-selfcheck', 'Api\DeviceController@storeSelfCheck');
Route::post('device-history', 'Api\DeviceController@getNearby');
Route::post('change-status', 'Api\DeviceController@changeRequest');

Route::get('/', function () {
    return response()->json([
        'message' => 'Hello Developer'
    ]);
});

Route::get('/call-centers', 'Api\InfoController@getCallCenters');
Route::get('/hospitals', 'Api\InfoController@getHospitals');
Route::get('/indonesia-statistics', 'Api\InfoController@getIndonesiaStatistics');
Route::get('/province-statistics', 'Api\InfoController@getProvinceStatistics');
Route::get('/gorontalo-statistics', 'Api\InfoController@getGorontaloStatistics');
Route::get('/partners', 'Api\InfoController@getPartners');
Route::post('/partners', 'Api\InfoController@reportPartners')->middleware('throttle:4,1');;
Route::post('/partners/sikm', 'Api\InfoController@sikm');

Route::get('/area/provinces', 'Api\AreaController@getProvinces');
Route::get('/area/{province}/cities', 'Api\AreaController@getCities');
Route::get('/area/{city}/districts', 'Api\AreaController@getDistricts');
Route::get('/area/{district}/villages', 'Api\AreaController@getVillages');
Route::get('/area/gorontalo', 'Api\AreaController@getGorontalo');
Route::get('/area/origin-cities', 'Api\AreaController@getOriginCities');
