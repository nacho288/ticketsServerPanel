<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Categoria;
use App\Subcategoria;
use App\Producto;
use App\Trato;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CanAccess;

class TestController extends Controller
{

    use CanAccess;

    public function test(Request $request)
    {


        /*         $productos = Producto::doesntHave('tratos')->select('productos.id', 'nombre', 'codigo', 'subcategoria_id', 'almacene_id', 'minimo', 'maximo', 'stock', 'alerta', 'frecuencia', 'preparacion');

        $productos2 = Producto::where('almacene_id', '=', 1)
            ->join('tratos', function ($join) {
                $join->on('productos.id', '=', 'tratos.producto_id')
                    ->where('tratos.oficina_id', '=', 1);
            })
            ->select('productos.id', 'nombre', 'codigo', 'subcategoria_id', 'almacene_id', 'tratos.minimo', 'tratos.maximo', 'stock', 'alerta', 'frecuencia', 'preparacion')
            ->union($productos)
            ->get();
 */

        $productos = Producto::where('almacene_id', '=', 1)->get(); 

        $tratos = Trato::where('oficina_id', '=', 1)->get();

        $respuesta = [];

        foreach ($productos as $producto) {

            $key = array_search($producto->id, array_column($tratos->toArray(), 'producto_id'));

            if (FALSE !== $key) {
                $producto->minimo = $tratos[$key]->minimo;
                $producto->maximo = $tratos[$key]->maximo;
            }

            $cantidad_actual = Pedido::where([['almacene_id', '=',1], ['oficina_id', '=', 1], ['estado', '!=', 4]])
                ->where('fecha', '<=', Carbon::now())
                ->where('fecha', '>=', Carbon::now()->subDays($producto->frecuencia))
                ->with('productos')
                ->get()
                ->pluck('productos')
                ->flatten()
                ->where('id', $producto->id)
                ->where('pivot.estado', '!=', 2)
                ->sum('pivot.cantidad');

            if ($producto->maximo != 0 && $producto->maximo > $cantidad_actual) {
                array_push($respuesta, $producto);
            }
        }

 

        return ['productos' =>  $productos , 'tratos' => $tratos, 'respuesta' => $respuesta];
            

    }
}
