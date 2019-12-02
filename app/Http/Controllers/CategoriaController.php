<?php

namespace App\Http\Controllers;

use App\Almacene;
use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
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
            return Categoria::where('almacene_id', $request->almacene_id)
                            ->with('subcategorias.productos')
                            ->orderBy('nombre')
                            ->get();   
        }

        if (
            $request->user()->type == 0 &&
            $request->user()->oficinas->where('oficina_id', $request->oficina_id)->first()->exists() &&
            Almacene::find(['id' => $request->almacene_id])
                            ->first()
                            ->oficinas
                            ->where('oficina_id', $request->oficina_id)
                            ->first()
                            ->exists() 
        ) {
            return Categoria::where('almacene_id', $request->almacene_id)->with('subcategorias')->orderBy('nombre')->get();   
            
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
        ){
            try {
                Categoria::create(
                    [
                        'nombre' => $request->nombre,
                        'almacene_id' => $request->almacene_id,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        try {
            $categoria
                ->fill($request->all())
                ->save();
        } catch (Exception $e) {
            return ["error" => true];
        }
        return ["error" => false];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Categoria $categoria)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists() &&
            $categoria->subcategorias->count() == 0
        ) {
            try {
                $categoria->delete();
            } catch (Exception $e) {
                return ["error" => true];
            }
            return ["error" => false];
        }
        return ["error" => true];
    }
}
