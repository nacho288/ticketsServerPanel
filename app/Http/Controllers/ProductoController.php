<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Movimiento;
use Illuminate\Http\Request;
use Exception;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::with('subcategoria.categoria')->get();;

        if ($productos) {
            return $productos;
        } else {
            return ["empy" => true];
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$request->has('stock')) {
            $request->stock = 0;
        }

        try {
            $producto = Producto::create($request->all());
        } catch (Exception $e) {
            return ["error" => true];
        }

        try {
            Movimiento::create(
                [
                    'usuario_id' => $request->usuario_id,
                    'producto_id' => $producto->id,
                    'original' => $request->stock,
                    'nuevo' => $request->stock,
                    'fecha' => date('Y-m-d H:i:s'),
                    'tipo_id' => "0",
                ]
            );
        } catch (Exception $e) {
            return ["error" => true];
        }

        return Producto::with('subcategoria.categoria')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return $producto->load('tratos', 'movimientos', 'subcategoria.categoria');
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
        if ($request->has('stock')) {
            if ($request->stock != $producto->stock) {
                Movimiento::create(
                    [
                        'usuario_id' => $request->usuario_id,
                        'producto_id' => $producto->id,
                        'original' => $producto->stock,
                        'nuevo' => $request->stock,
                        'fecha' => date('Y-m-d H:i:s'),
                        'tipo_id' => "1",
                    ]);
            }
        }

        try {
            $producto
                ->fill($request->all())
                ->save();
        } catch (Exception $e) {
            return ["error" => true];
        }

        return Producto::with('subcategoria.categoria')->get();
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
