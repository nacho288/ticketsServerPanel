<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Categoria;
use App\Subcategoria;
use App\Producto;
use App\Trato;
use Carbon\Carbon;
use App\Almacene;
use Illuminate\Http\Request;
use App\Traits\CanAccess;

class CodigoController extends Controller
{

    public function formatear(Request $request)
    {
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

        return ['respuesta' => '$respuesta'];
            

    }
}
