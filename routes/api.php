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
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('users', 'UserController@index');
Route::post('quotitation', 'RequestQuotitationController@store');
Route::get('quotitations', 'RequestQuotitationController@index');

/**recibe un id de solitud de aquicion y responde con los detalles que perteneces a esa solicitud */
Route::get('quotitation/{id}', 'RequestQuotitationController@show');

Route::put('quotitation/status/{id}', 'RequestQuotitationController@updateState');

/**recibe un id de solicitud y responde con los archivos adjuntos que pertenecen a esa solicitud */
Route::get('requestQuotitation/files/{id}', 'RequestQuotitationController@showFiles');

Route::post('report/{id}', 'ReportController@store');
Route::post('upload/{id}', 'RequestQuotitationController@uploadFile');
Route::get('download', 'RequestQuotitationController@download');
Route::post('details', 'UserController@details');

/**resive los emails y la descripcion del mensage que se enviara a las empresas o a la empresa
 * y resive el id a la solicitud a la que pertenece*/
Route::post('sendEmail/{id}','EmailController@store');

Route::group(['middleware' => 'auth:api'], function(){
    
});
