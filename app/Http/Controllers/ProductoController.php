<?php

namespace App\Http\Controllers;

use App\Almacene;
use App\Producto;
use App\Subcategoria;
use App\Movimiento;
use App\Oficina;
use App\Trato;
use Illuminate\Http\Request;
use Exception;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        

        if (
            $request->user()->type == 1 && 
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ){

            $productos = Producto::where('almacene_id', $request->almacene_id)->with('subcategoria.categoria')->get();

            if ($request->has('crear')) {

                try {
                    $tratos = Trato::where('oficina_id', '=', $request->oficina_id)->get();

                    foreach ($productos as $producto) {

                        $key = array_search($producto->id, array_column($tratos->toArray(), 'producto_id'));

                        if (FALSE !== $key) {
                            $producto->minimo = $tratos[$key]->minimo;
                            $producto->maximo = $tratos[$key]->maximo;
                        }
                    }

                    return $productos->where('maximo', '>', '0');
                } catch (Exception $e) {
                    return ["error" => 'true'];
                }

            }

            if ($productos) {
                return $productos;
            } else {
                return ["empy" => true];
            }
            
        } 
        
        if ($request->user()->type == 0){

            try {
                $almacen = Almacene::where('id', $request->almacene_id)->with('oficinas.usuarios', 'productos')->first();
            } catch (Exception $e) {
                return ["error" => true];
            }

            if ($almacen->oficinas->where('id', $request->oficina_id)->first()->usuarios->where('id', $request->user()->id)->first()->exists()) {

                try {
                    $productos = $almacen->productos;
                    $tratos = Trato::where('oficina_id', '=', $request->oficina_id)->get();

                    foreach ($productos as $producto) {

                        $key = array_search($producto->id, array_column($tratos->toArray(), 'producto_id'));

                        if (FALSE !== $key) {
                            $producto->minimo = $tratos[$key]->minimo;
                            $producto->maximo = $tratos[$key]->maximo;
                        }
                    }

                    return $productos->where('maximo', '>', '0');

                } catch (Exception $e) {
                    return ["error" => 'true'];
                }

            }

            return ["error" => true]; 

        }
           
        return ["error" => true]; 
  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {

            if (!$request->has('stock')) {
                $request->stock = 0;
            }

            $cadena = substr(Subcategoria::find($request->subcategoria_id)->categoria->nombre, 0, 1) .
                      substr(Subcategoria::find($request->subcategoria_id)->nombre, 0, 1) .
                      substr($request->nombre, 0, 1);

            $numero = 0;
        
            while (Producto::where('codigo', strtoupper($cadena) . $numero)->exists()) {
                $numero++;
            }

            $codigo = strtoupper($cadena) . $numero;

            try {
                $request->codigo = $codigo;
                $producto = Producto::create(
                    [
                        'nombre' => $request->nombre,
                        'codigo' => $codigo,
                        'subcategoria_id' => $request->subcategoria_id,
                        'almacene_id' => $request->almacene_id,
                        'minimo' => $request->minimo,
                        'maximo' => $request->maximo,
                        'stock' => $request->stock,
                        'alerta' => $request->alerta,
                        'frecuencia' => $request->frecuencia,
                        'preparacion' => $request->preparacion,
                    ]
                );
            } catch (Exception $e) {
                return ["error" => true];
            }

            try {
                Movimiento::create(
                    [
                        'user_id' => $request->user_id,
                        'producto_id' => $producto->id,
                        'original' => $request->stock,
                        'nuevo' => $request->stock,
                        'fecha' => date('Y-m-d H:i:s'),
                        'tipo' => "0",
                    ]
                );
            } catch (Exception $e) {
                return ["error" => true];
            }

            return ["error" => false];
            
        } 
            
        return ["error" => true];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Producto $producto)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {

            try {
                return $producto->load('tratos', 'movimientos', 'subcategoria.categoria');
            } catch (Exception $e) {
                return ["error" => true];
            }

        } else {
            return ["error" => true];
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {

            if ($request->has('stock')) {
                if ($request->stock != $producto->stock) {
                    Movimiento::create(
                        [
                            'user_id' => $request->user_id,
                            'producto_id' => $producto->id,
                            'original' => $producto->stock,
                            'nuevo' => $request->stock,
                            'fecha' => date('Y-m-d H:i:s'),
                            'tipo' => "1",
                            'comentario' => $request->comentario,
                        ]
                    );
                }
            }

            try {
                $producto
                    ->fill($request->all())
                    ->save();
            } catch (Exception $e) {
                return ["error" => true];
            }

        }

        return ["error" => false];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();
        } catch (Exception $e) {
            return ["error" => true];
        }
        return Producto::all();
    }
}
