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

    //users
    Route::post('/usuario/listar','UserController@list');

    //products
    Route::post('/producto/listar','ProductController@index');
    Route::post('/producto/crear','ProductController@store');
    Route::post('/producto/buscar','ProductController@show');
    Route::post('/producto/actualizar','ProductController@update');
    Route::post('/producto/eliminar','ProductController@destroy');

    //Brands
    Route::post('/marca/listar','ProductBrandController@index');
    Route::post('/marca/crear','ProductBrandController@store');
    Route::post('/marca/buscar','ProductBrandController@show');
    Route::post('/marca/actualizar','ProductBrandController@update');
    Route::post('/marca/eliminar','ProductBrandController@destroy');

    //Category
    Route::post('/categoria/listar','ProductCategoryController@index');
    Route::post('/categoria/crear','ProductCategoryController@store');
    Route::post('/categoria/buscar','ProductCategoryController@show');
    Route::post('/categoria/actualizar','ProductCategoryController@update');
    Route::post('/categoria/eliminar','ProductCategoryController@destroy');

    //Sale
    Route::post('/venta/listar','SalesController@index');
    Route::post('/venta/crear','SalesController@store');
    Route::post('/venta/buscar','SalesController@show');
    Route::post('/venta/actualizar','SalesController@update');
    Route::post('/venta/eliminar','SalesController@destroy');

    //dashboard
    Route::post('/dashboard/ventas','DashboardController@sales');
    Route::post('/dashboard/productos','DashboardController@salesproducts');

});
