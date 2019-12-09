<?php

namespace App\Http\Controllers;

use App\Trato;
use App\Producto;
use Exception;

use Illuminate\Http\Request;

class TratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            try {
                $consulta =  Trato::where('oficina_id', $request->oficina_id
                    )->where('producto_id', $request->producto_id);

                if ($consulta->exists()) {
                    $tratoViejo = $consulta->first();
                    $tratoViejo->minimo = $request->minimo;
                    $tratoViejo->maximo = $request->maximo;
                    $tratoViejo->save();
                } else {
                    Trato::create($request->all());
                }
            } catch (Exception $e) {
                return ["error" => true];
            }

            return Producto::where('almacene_id', $request->almacene_id)
                            ->with('subcategoria.categoria')
                            ->get();
            
        }

        return ["error" => true];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trato  $trato
     * @return \Illuminate\Http\Response
     */
    public function show(Trato $trato)
    {
        return $trato;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trato  $trato
     * @return \Illuminate\Http\Response
     */
    public function edit(Trato $trato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trato  $trato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trato $trato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trato  $trato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,Trato $trato)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {

            try {
                $trato->delete();
            } catch (Exception $e) {
                return ["error" => true];
            }

            return Producto::where('almacene_id', $request->almacene_id)
                ->with('subcategoria.categoria')
                ->get();
        }

        return ["error" => true];
    }
}
