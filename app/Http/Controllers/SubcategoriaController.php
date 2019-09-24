<?php

namespace App\Http\Controllers;

use App\Subcategoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
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
                Subcategoria::create($request->all());
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
     * @param  \App\Subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategoria $subcategoria)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {
            try {
                $subcategoria
                    ->fill($request->all())
                    ->save();
            } catch (Exception $e) {
                return ["error" => true];
            }
            return ["error" => false];
        }

        return ["error" => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategoria $subcategoria)
    {
        //
    }
}
