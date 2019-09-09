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
    Route::apiResource('usuarios', 'UsuarioController');
    Route::apiResource('productos', 'ProductoController');
    Route::apiResource('pedidos', 'PedidoController');
    Route::apiResource('tratos', 'TratoController');
    Route::apiResource('categorias', 'CategoriaController');
    Route::apiResource('subcategorias', 'SubcategoriaController');

    Route::post('/conectar', 'ConectarController@conectar');
    Route::get('/uproductos', 'ProductoUsuarioController@productos');
});





