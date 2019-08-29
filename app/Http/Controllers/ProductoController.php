<?php

namespace App\Http\Controllers;

use App\Producto;
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
        $productos = Producto::all();

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

        try {
            Producto::create($request->all());
        } catch (Exception $e) {
            return ["error" => true];
        }

        return Producto::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return $producto->load('tratos');
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
        try {
            $producto
                ->fill($request->all())
                ->save();
        } catch (Exception $e) {
            return ["error" => true];
        }

        return Producto::all();
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
