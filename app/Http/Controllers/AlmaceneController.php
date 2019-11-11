<?php

namespace App\Http\Controllers;

use App\Almacene;
use Illuminate\Http\Request;

class AlmaceneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $almacenes = Almacene::orderBy('nombre')->with('oficinas', 'administradores')->get();

        if ($almacenes) {
            return $almacenes;
        } else {
            return ["empy" => true];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Almacene  $almacene
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Almacene $almacene)
    {
        if (
            $request->user()->type == 1 &&
            $request->user()->almacenes()->where('almacene_id', $almacene->id)->exists()
        ) {
            try {
                return $almacene->load('administradores', 'oficinas.usuarios');
            } catch (Exception $e) {
                return ["error" => true];
            }
        } else {
            return ["error" => true];
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
                Almacene::create(['nombre' => $request->nombre]);
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
     * @param  \App\Almacene  $almacene
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Almacene $almacene)
    {
        if ($request->user()->type == 9) {

            if ($request->action == '0') {
                try {
                    $almacene->nombre = $request->nombre;
                    $almacene->save();
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false]; 
            }

            if ($request->action == '1') {
                try {
                    $almacene->oficinas()->attach($request->oficina_id);
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

            if ($request->action == '2') {
                try {
                    $almacene->oficinas()->detach($request->oficina_id);
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

            if ($request->action == '3') {
                try {
                    $almacene->administradores()->attach($request->administrador_id);
                } catch (Exception $e) {
                    return ["error" => true];
                }
                return ["error" => false];
            }

            if ($request->action == '4') {
                try {
                    $almacene->administradores()->detach($request->administrador_id);
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
     * @param  \App\Almacene  $almacene
     * @return \Illuminate\Http\Response
     */
    public function destroy(Almacene $almacene)
    {
        //
    }
}
