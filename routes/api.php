<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'UserController@login')->middleware('throttle:5,1');
    Route::post('register', 'UserController@saveUser');
});

Route::get('soap/users', 'UserController@getUserSoap');

Route::put('user/recover/password/rut/{rut}', 'UserController@recoverPassword');
Route::get('terms', 'HomeController@termn');
Route::put('term/change/flag', 'HomeController@changeFlag');

Route::put('user/recovery/password/id', 'UserController@saveRecoveryPassword');
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'service'], function () {
        Route::get('current/settlement', 'ServiceController@currentSettlement');
        Route::get('terms', 'ServiceController@isAcceptanceTermsText');
        Route::post('acceptance/terms', 'ServiceController@isAcceptanceTerms');
    });

    Route::get('suspend/user', 'UserController@suspendUser');

    Route::get('work/table/tutorial', 'WorkTableController@tutorial');
    Route::get('work/table/faq', 'WorkTableController@faq');
    Route::get('work/table/contact', 'WorkTableController@contact');

    Route::get('onboarding', 'UserController@onboarding');
    Route::get('send/pdf', 'ServiceController@sendPdf');

    Route::get('latest/settlements', 'UserController@latestSettlements');
    Route::get('logout', 'UserController@logoutApi');
    Route::get('certificate', 'CertificateController@certificate');
    Route::get('certificados/renta-1887', 'CertificateController@certificateRent');

    Route::put('user/home/term', 'HomeController@updateUserTermCondition');
    Route::get('user/home/term', 'HomeController@isCheckTermn');

    Route::put('user/service/term', 'ServiceController@updateUserTermCondition');
    Route::put('user/notification', 'UserController@updateUserNotification');

    Route::patch('user/read/notification', 'NotifyController@readNotification');

    Route::get('service/settlements', 'ServiceController@settlements');

    Route::get('user/notification', 'NotifyController@getNotification');

    Route::get('user/service/term', 'ServiceController@isCheckTermn');

    Route::patch('unlink/user/{rut}', 'UserController@unlinkUser');
    Route::get('services', 'ServiceController@index');
    Route::get('me', 'UserController@profile');

    Route::put('/user/register/full', 'HomeController@registerFull');

    //Constribution
    Route::get('covid/ammounts', 'ConstributionController@index');
    Route::post('covid/user/ammounts', 'ConstributionController@voluntaryContribution');
    // Text controller

    //Route::get('region', 'RegionController@index');
    //Route::get('commune/{id}', 'CommuneController@communeByRegion');
    //Route::post('pay', 'PayController@savePurchase');
    //Route::get('pay', 'PayController@get');
    //Route::delete('pay/{id}', 'PayController@delete');
    //Route::get('order/history', 'PayController@orderHistory');
    Route::post('user/complete', 'UserController@completeData');
    //Route::post('user/complete', 'UserController@completeData');

});

Route::get('materials/categories', 'CategoryController@index');

Route::get('educational/materials/categories/{slug}/items', 'CategoryController@categoriesItem');

Route::get('/text', 'TextController@handle');

Route::post('send', 'NotifyController@send');
Route::get('releases', 'ReleaseController@index');
Route::get('educational/materials/activity', 'EducationlMaterialController@indexActivity');
Route::get('educational/materials/{slug}', 'EducationlMaterialController@showActivity');

Route::get('educational/materials/workshop', 'EducationlMaterialController@indexWorkshop');
Route::get('educational/materials/workshop/{slug}', 'EducationlMaterialController@showWorkshop');

Route::get('educational/materials/video', 'EducationlMaterialController@video');
Route::get('job/offers', 'ReleaseController@jobOffers');
Route::get('posts', 'PostController@index');
Route::get('post/{slug}', 'PostController@show');

Route::get('benefits', 'BenefitController@index');
Route::get('benefit/{slug}', 'BenefitController@show');

Route::get('surveys', 'SurveysController@index');
Route::get('survey/{slug}', 'SurveysController@show');
Route::get('release/{release}', 'ReleaseController@show');
Route::get('home', 'HomeController@index');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
