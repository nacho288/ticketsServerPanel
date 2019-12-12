<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Oficina;
use Carbon\Carbon;
use App\Traits\CanAccess;
use Illuminate\Http\Request;
use Exception;


class ResumenOficina extends Controller
{
    use CanAccess;

    public function resumen(Request $request)
    {

        $producto;

        try {
            $producto = Producto::findOrFail($request->producto_id);
        } catch (Exception $e) {
            return ["error" => true];
        }

        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $producto->almacene_id)->exists()
        ) {
            try {
                
                $pedidos = $producto->pedidos()
                ->where('oficina_id', '=', $request->oficina_id)
                ->whereDate('fecha', '<=', Carbon::today()->toDateString())
                ->whereDate('fecha', '>', Carbon::today()->subDays($producto->frecuencia)->toDateString())
                ->get()
                ->where('pivot.estado', '!=', 2);

                $trato = $producto->tratos->where('oficina_id', '=', $request->oficina_id)->first();

                $excepcionales = $producto
                    ->excepcionales()
                    ->whereDate('inicio', '<=', Carbon::today())
                    ->whereDate('final', '>=', Carbon::today())
                    ->where('oficina_id', '=', $request->oficina_id)->get();

                $oficina = Oficina::find($request->oficina_id);

                $excepcionalesTotal = $excepcionales->sum('cantidad');

                $total = $trato ? $trato->maximo + $excepcionalesTotal : $producto->maximo + $excepcionalesTotal; 

                return [
                    'oficina' => $oficina,
                    'cantidades' => ['minimo' => $producto->minimo, 'maximo' =>  $producto->maximo],
                    'trato' => $trato,
                    'excepcionales' => $excepcionales,
                    'excepcionalesTotal' => $excepcionalesTotal,
                    'pedidos' => $pedidos,
                    'sumatoriaPedidos' => $pedidos->sum('pivot.cantidad'),
                    'total' => $total,
                ]; 

            } catch (Exception $e) {
                return ["error" => true];
            }

        } else {
            return ["error" => true];
        }

    }
}
