<?php

namespace App\Http\Controllers;

use App\Subcategoria;
use App\Producto;
use App\Almacene;
use Illuminate\Http\Request;
use Exception;

class CodigoController extends Controller
{

    public function formatear(Request $request)
    {

        if ($request->user()->type == 9) {

            try {
                $almacenes = Almacene::all();

                foreach ($almacenes as $almacen) {
                    $productos = Producto::where('almacene_id', '=', $almacen->id)->get();

                    foreach ($productos as $producto) {

                        $cadena = substr(Subcategoria::find($producto->subcategoria_id)->categoria->nombre, 0, 1) .
                            substr(Subcategoria::find($producto->subcategoria_id)->nombre, 0, 1) .
                            substr($producto->nombre, 0, 1);

                        $numero = 0;

                        while (Producto::where('codigo', strtoupper($cadena) . $numero)->exists()) {
                            $numero++;
                        }

                        $codigo = strtoupper($cadena) . $numero;

                        $producto->codigo = $codigo;

                        $producto->save();
                    }
                }
            } catch (Exception $e) {
                return ["error" => true];
            }
            return ["error" => false];

        }

        return ["error" => true];

    }
}
