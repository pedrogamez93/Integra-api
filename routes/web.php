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

Route::get('test', 'ServiceController@test');

Route::get('file/open/pdf/{file}', 'ServiceController@fileOpenPdf');

Route::get('notificate', 'UserController@notificate');

Route::get('cambiar/contrasena/user/{id}', 'UserController@formRecoveryPassword');

Route::get('encuesta-01', function () {
    return view('pages.survey');
});

Route::get('cache', function () {
    \Cache::flush();
});

Route::get('service/current/settlement', 'ServiceController@currentSettlement');

Route::get('certificate', 'CertificateController@test');

Route::get('desastivar-usuario', function () {
    return view('pages.userdisabled');
});
Route::post('desastivar-usuario', 'UserController@requestUserDelete')->name('desastivar.usuario');
