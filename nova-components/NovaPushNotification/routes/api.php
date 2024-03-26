<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
 */

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::post('/send', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@send');
Route::get('/positions', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getPosition');
Route::get('/districts', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getDistrict');
Route::get('/regions', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getRegions');

Route::get('/posts', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getPost');
Route::get('/releases', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getRelease');
Route::get('/jobOffers', 'Meat\NovaPushNotification\Http\Controllers\PushNotificationController@getJobOffers');
