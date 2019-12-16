<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Trato;


class ProductoUsuarioController extends Controller
{
    public function productos(Request $request)
    {

        $tratos = Trato::where('user_id', '=', $request->user_id)->get();

        $productos = Producto::all();

        foreach ($tratos as $trato) {

        $key = array_search($trato->producto_id, array_column($productos->toArray(), 'id'));   
        
        $productos[$key]->minimo = $trato->minimo;
        $productos[$key]->maximo = $trato->maximo;

        }

        return $productos;

    }

}
