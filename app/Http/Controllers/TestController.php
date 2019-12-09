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

        $consulta =  Trato::where('oficina_id', 22)->where('producto_id', 397);

        $primero =  $consulta->first();

        return [$consulta->exists(),
        'asd' => $primero
    ];
    
    }
}
