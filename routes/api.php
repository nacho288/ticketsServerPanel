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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */

Route::group(['middleware' => 'cors'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');

            Route::apiResource('usuarios', 'UsuarioController');
            Route::apiResource('productos', 'ProductoController');
            Route::apiResource('pedidos', 'PedidoController');
            Route::apiResource('tratos', 'TratoController');
            Route::apiResource('categorias', 'CategoriaController');
            Route::apiResource('subcategorias', 'SubcategoriaController');
            Route::apiResource('almacenes', 'AlmaceneController');
            Route::apiResource('oficinas', 'OficinaController');
            Route::apiResource('movimientos', 'MovimientoController');

            Route::post('/conectar', 'ConectarController@conectar');
            Route::get('/uproductos', 'ProductoUsuarioController@productos');
            Route::get('/test', 'TestController@test');
        });
    });

});







