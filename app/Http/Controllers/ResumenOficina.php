<?php

namespace App\Http\Controllers;

use App\Producto;
use Carbon\Carbon;
use App\Traits\CanAccess;
use Illuminate\Http\Request;

class ResumenOficina extends Controller
{
    use CanAccess;

    public function resumen(Request $request)
    {
        $producto = Producto::findOrFail($request->producto_id);

        $pedidos = $producto->pedidos()
        ->where('oficina_id', '=', $request->oficina_id)
        ->whereDate('fecha', '<=', Carbon::today()->toDateString())
        ->whereDate('fecha', '>', Carbon::today()->subDays($producto->frecuencia)->toDateString())
        ->get()
        ->where('pivot.estado', '!=', 2);

        $trato = $producto->tratos->where('oficina_id', '=', $request->oficina_id)->first();

        $exepcionales = $producto
            ->excepcionales()
            ->whereDate('inicio', '<=', Carbon::today())
            ->whereDate('final', '>=', Carbon::today())
            ->where('oficina_id', '=', $request->oficina_id)->get();;

        return [
            'cantidades' => ['minimo' => $producto->minimo, 'maximo' =>  $producto->maximo],
            'trato' => $trato,
            'exepcionales' => $exepcionales,
            'exepcionalesTotal' => $exepcionales->sum('cantidad'),
            'pedidos' => $pedidos,
            'total' => $pedidos->sum('pivot.cantidad'),
        ]; 
        
    }
}
