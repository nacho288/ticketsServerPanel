<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Categoria;
use App\Subcategoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\CanAccess;

class TestController extends Controller
{

    use CanAccess;

    public function test(Request $request)
    {
        /* 
        return Pedido::where([['almacene_id', '=', 1],['oficina_id', '=', 1], ['estado', '!=', 4]])
            ->whereYear('fecha', '=', date('Y'))
            ->whereMonth('fecha', '=', date('m'))
            ->with('productos')
            ->get()
            ->pluck('productos')
            ->flatten()
            ->where('id', 1)
            ->where('pivot.estado', '!=', 3)
            ->sum('pivot.cantidad'); */

        /* 
            $frecuencia = 20;

        $fecha_actual = date("d-m-Y");

        $fecha_1 = date("d-m-Y", strtotime($fecha_actual . "- " . $frecuencia . " days"));  */

        $cantidad_actual = Pedido::where([['almacene_id', '=', 1], ['oficina_id', '=', 1], ['estado', '!=', 4]])
            ->where('fecha', '<=', Carbon::now())
            ->where('fecha', '>=', Carbon::now()->subDays(5))
            ->with('productos')
            ->get()
            ->pluck('productos')
            ->flatten()
            ->where('id', 1)
            ->where('pivot.estado', '!=', 2)
            ->sum('pivot.cantidad');

            return ['respuesta' =>  $cantidad_actual];
    
    }
}
