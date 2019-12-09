<?php

namespace App\Http\Controllers;

use App\Excepcionale;
use Illuminate\Http\Request;
use Exception;

class ExcepcionaleController extends Controller
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
                Excepcionale::create($request->all());
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
     * @param  \App\Excepcionale  $excepcionale
     * @return \Illuminate\Http\Response
     */
    public function show(Excepcionale $excepcionale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Excepcionale  $excepcionale
     * @return \Illuminate\Http\Response
     */
    public function edit(Excepcionale $excepcionale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Excepcionale  $excepcionale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Excepcionale $excepcionale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Excepcionale  $excepcionale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Excepcionale $excepcionale)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {

            try {
                $excepcionale->delete();
            } catch (Exception $e) {
                return ["error" => true];
            }

            return ["error" => false];
        }

        return ["error" => true];
    }
}
