<?php

namespace App\Http\Controllers;

use App\Oficina;
use App\Almacene;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->type == 9) {    
            return Oficina::orderBy('nombre')->with('usuarios')->get();
        }

        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $request->almacene_id)->exists()
        ) {
            return Almacene::where('id', $request->almacene_id)->firstOrFail()->oficinas->orderBy('nombre')->get();
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
        if ($request->user()->type == 9) {
            try {
                $oficina = new Oficina();
                $oficina->nombre = $request->nombre;
                $oficina->save();
                /* $oficina->usuarios()->attach(2);
                $oficina->almacenes()->attach(1); */
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
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Oficina $oficina)
    {
        if ($request->user()->type == 9) {

            if ($request->action == '0') {
                try {
                    $oficina->nombre = $request->nombre;
                    $oficina->save();
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

            if ($request->action == '1') {
                try {
                    $oficina->usuarios()->attach($request->user_id);
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

            if ($request->action == '2') {
                try {
                    $oficina->usuarios()->detach($request->user_id);
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

        }

        return ["error" => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Oficina  $oficina
     * @return \Illuminate\Http\Response
     */
    public function destroy(Oficina $oficina)
    {
        //
    }
}
