<?php

namespace App\Http\Controllers;

use App\Pedido;
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

            return ['respuesta' => $this->CanAdminAlmacen($request->user(), 1)];

    
    }
}
