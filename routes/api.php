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

/**CopanyCode */
/**resive el codigo y lo busca*/
Route::post('searchCode','CompanyCodeController@searchCode');

/**Dentro de este grupo de rutas solo podran acceder si han iniciado sesion por lo tanto tiene que 
 * pasar el token para poder usar las rutas dentro del grupo
 */
Route::group(['middleware' => 'auth:api'], function(){
    /**UserController */
    /**Devuelve todos los detalles del usuario cuando inicia sesion */
    Route::post('details', 'UserController@details');
    /**Recibe el request con los datos del usuario y aparte el id del rol de usuario para registrar el nuevo usuario */
    Route::post('register/{id}', 'UserController@register');
    /**deverlve los permisos del usuario */
    Route::post('permissions','UserController@permissions');
    /**deverlve los roles del usuario */
    Route::post('roles','UserController@roles');
    /**Responde con los datos (mas el rol) de todos los usuarios registrados (listado de usuarios)*/
    Route::get('users', 'UserController@index');

    /**contization */
    Route::get('quotitations', 'RequestQuotitationController@index');
    Route::post('quotitation', 'RequestQuotitationController@store');
    /**Muestra datos del usuario que va a realizar una solicitud */
    Route::get('getInform','RequestQuotitationController@getInformation');

    /**recibe un id de solitud de adquicion y responde con los detalles que perteneces a esa solicitud, 
     * mas un campo que guarda el mensaje de si el monto estimado es superior al monto limite*/
    Route::get('quotitation/{id}', 'RequestQuotitationController@show');

    Route::put('quotitation/status/{id}', 'RequestQuotitationController@updateState');

    /**recibe un id de solicitud y responde con los archivos adjuntos que pertenecen a esa solicitud */
    Route::get('requestQuotitation/files/{id}', 'RequestQuotitationController@showFiles');

    Route::post('report/{id}', 'ReportController@store');
    Route::post('upload/{id}', 'RequestQuotitationController@uploadFile');
    Route::get('download', 'RequestQuotitationController@download');

    /**resive los emails y la descripcion del mensage que se enviara a las empresas o a la empresa
     * y resive el id a la solicitud a la que pertenece*/
    Route::post('sendEmail/{id}','EmailController@store');
    Route::post('sendEmail','EmailController@store');

    /**ROL CONTROLLER */
    /**Devuleve la lista de todos los roles */
    Route::get('rols', 'RoleController@index');
    /**Recibe el id del usuario y el id del rol y modifica el rol de un usuario */
    Route::put('users/update/{idu}/{idr}', 'UserController@updateRol');
    /**Recibe el nombre y descripcion del nuevo rol para guardarlo */
    Route::post('rols/new', 'RoleController@store');


    /**LIMITE CONTROLLER */
    //Registra un monto limite
    Route::post('limiteAmount/new','LimiteAmountController@register');
    //Actualiza un nuevo monto limite dado un id de la unidad administrativa a la que pertenece
    Route::post('updateLimiteAmount/{id}','LimiteAmountController@updateLimiteAmount');
    //Devuelve lista de los montos limites dado un id de la unidad administrativa a la que pertenece
    Route::get('limiteAmounts/{id}','LimiteAmountController@show');
    //Devuel todos los montos
    Route::get('limiteAmout','LimiteAmountController@index');
    //Devuelve el registro actual de los montos limites dado un id de la unidad administrativa a la que pertenece
    Route::get('lastRecord/{id}','LimiteAmountController@sendCurrentData');

    /**Faculty Controller */
    /**devuelve las facultades */
    Route::get('faculties','FacultyController@index');
    // Devuelve todas las facultades de la base de datos
    Route::get('Faculties','FacultyController@index');
    // Devuelve solo las facultades que no estan asignadas a una unidad administrativa
    Route::get('faculties/use','FacultyController@noUseFaculties');
    //Crea una nueva facultad 
    Route::post('facultad/new','FacultyController@store');


    //Registra una unidad administrativa
    Route::post('administrativeUnit/new','AdministrativeUnitController@register');
    /**Devuelve la lista de todos las unidades administrativas */
    Route::get('administrativeUnit','AdministrativeUnitController@index');


    /**Recibe el nombre de la unidad de gasto y la id de la FACULTAD dentro de un request para guardarlo */
    Route::post('spendingUnits/new','SpendingUnitController@store');
    /**Devuelve la lista de todos las unidades de gasto con su facultad y unidad administrativa correspondiente*/
    Route::get('spendingUnits','SpendingUnitController@index');

    
    /**EMPRESA CONTROLLER */
     /**Recibe los datos de una empresa dentro de un request para guardarlo */
    Route::post('business/new','BusinessController@store');
     /**Devuelve la lista de todos las empresas*/
    Route::get('business','BusinessController@index');
});
