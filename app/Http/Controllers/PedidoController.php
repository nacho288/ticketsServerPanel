<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Producto;
use App\Usuario;
use App\Trato;


use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $usuario_id = $request->usuario_id;
        $usuario = Usuario::findOrFail($usuario_id);

        $pedidos = $usuario->tipo != 0 ? $pedido = Pedido::all() : Pedido::where('usuario_id', '=', $usuario_id)->get();;

        $pack = [];

        foreach ($pedidos as $pedido) {

            $aprovadoPor = $pedido->aprovadoPor ? $pedido->aprovadoPor->nombre : "";

            $values = [
                "pedido_id" => $pedido->id,
                "usuario" => $pedido->usuario->nombre,
                "aprovadoPor" => $aprovadoPor,
                "estado" => $pedido->estado,
                "fecha" => $pedido->fecha,
            ];

            array_push($pack, $values);
        }

        return $pack;

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pedido = new Pedido();
        $pedido->usuario_id = $request->usuario_id;
        $pedido->comentario_usuario = $request->comentario_usuario;
        $pedido->fecha =  date('Y-m-d H:i:s');
        $pedido->estado = 0;
        $pedido->save();

        $aprobado= true;

        $tratos = Trato::where('usuario_id', '=', $request->usuario_id)->get();
        
        foreach ($request->productos as $item) {

            $producto = Producto::findOrFail($item['producto_id']);

            $key = array_search($producto->id, array_column($tratos->toArray(), 'producto_id'));   

            $minimo = $producto->minimo;
            $maximo = $producto->maximo;

            if (FALSE !== $key) {
                $minimo = $tratos[$key]->minimo;
                $maximo = $tratos[$key]->maximo;
            }

            if ($minimo > $item['cantidad'] || $maximo < $item['cantidad']) {
                $aprobado = false;
            }

            $pedido->productos()->attach($item['producto_id'], ['cantidad' => $item['cantidad']]);
        }

        $pedido->estado = $aprobado ? 1 : 0;
        $pedido->save();

        return ["aprobado" => $aprobado];


    }
    
    public function show(Pedido $pedido)
    
    {
        $pro_pack = [];

        foreach ($pedido->productos as $producto) {

            $values = [
                "producto_id" => $producto->pivot->producto_id,
                "codigo" => $producto->codigo,
                "nombre" => $producto->nombre,
                "cantidad" => $producto->pivot->cantidad,
                "minimo" => $producto->minimo,
                "minimo" => $producto->minimo,
            ];

            array_push($pro_pack, $values);

        }

        $aprovadoPor = $pedido->aprovadoPor ? $pedido->aprovadoPor->nombre : "";

        $output = [
            "pedido_id" => $pedido->id,
            "estado" => $pedido->estado,
            "usuario_nombre" => $pedido->usuario->nombre,
            "aprobado_por" => $aprovadoPor,
            "comentario_usuario" => $pedido->comentario_usuario,
            "comentario_administrador" => $pedido->comentario_administrador,
            "fecha" => $pedido->fecha,
            "productos" => $pro_pack
        ];


        return $output;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        try {

            $pedido->estado = $request->estado;
            
            if ($request->estado == 1 || $request->estado == 4) {
                $pedido->comentario_administrador = $request->comentario_administrador ?? "";
                $pedido->aprovado_por = $request->aprovado_por;
            }
            
            $pedido->save();

        } catch (Exception $e) {
            return ["error" => true];
        }

        return ["sucess" => true];;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
