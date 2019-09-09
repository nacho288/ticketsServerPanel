<?php

namespace App\Http\Controllers;

use App\Trato;
use App\Producto;

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
        try {
            Trato::create($request->all());
        } catch (Exception $e) {
            return ["error" => true];
        }

        return Producto::with('subcategoria.categoria')->get();

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
    public function destroy(Trato $trato)
    {
        try {
            $trato->delete();
        } catch (Exception $e) {
            return ["error" => true];
        }
        return Producto::with('subcategoria.categoria')->get();
    }
}
