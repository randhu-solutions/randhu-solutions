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

Route::post('/usuario/registrar','UserController@createuser');
Route::post('/usuario/login','Auth\LoginController@dologin');


Route::group(['middleware' => ['token']], function () {

    //products
    Route::post('/producto/listar','ProductController@index');
    Route::post('/producto/crear','ProductController@store');
    Route::post('/producto/buscar','ProductController@show');
    Route::post('/producto/actualizar','ProductController@update');
    Route::post('/producto/eliminar','ProductController@destroy');

});
