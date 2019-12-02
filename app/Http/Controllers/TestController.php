<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Categoria;
use App\Subcategoria;
use App\Producto;
use App\Trato;
use App\Excepcionale;

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



    /* 

        return ['respuesta' => Carbon::today()->subDays(2)->toDateString()];
 */

        return ['respuesta' => Pedido::all()->first()->fecha >= Carbon::today()->subDays(2)->toDateString()];
            

    }
}
