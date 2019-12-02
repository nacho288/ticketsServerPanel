<?php

namespace App\Http\Controllers;

use App\Almacene;
use App\Producto;
use App\Subcategoria;
use App\Movimiento;
use App\Pedido;
use App\Trato;
use App\Excepcionale;
use Carbon\Carbon;
use App\Traits\CanAccess;
use Illuminate\Http\Request;
use Exception;

class ProductoController extends Controller
{
    use CanAccess;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        if ($request->user()->type == 0) {
            if (!$this->CanUserAlmacen($request->user(), $request->oficina_id, $request->almacene_id)) {
                return ["error" => 'true'];
            }
        }

        if ($request->user()->type == 1) {
            if (!$this->CanAdminAlmacen($request->user(), $request->almacene_id)) {
                return ["error" => 'true'];
            }
        }

        $productos = Producto::where('almacene_id', $request->almacene_id)->with('subcategoria.categoria')->orderBy('nombre')->get();

        if ($request->user()->type == 0 || $request->has('crear')) {

            try {
                $tratos = Trato::where('oficina_id', '=', $request->oficina_id)->get();
                $excepcionales = Excepcionale::where('oficina_id', '=', $request->oficina_id)
                    ->whereDate('inicio', '<=', Carbon::today())
                    ->whereDate('final', '>=', Carbon::today())
                    ->get();

                $respuesta = [];

                foreach ($productos as $producto) {

                    $key = array_search($producto->id, array_column($tratos->toArray(), 'producto_id'));

                    if (FALSE !== $key) {
                        $producto->minimo = $tratos[$key]->minimo;
                        $producto->maximo = $tratos[$key]->maximo;
                    }

                    $key2 = array_search($producto->id, array_column($excepcionales->toArray(), 'producto_id'));

                    if (FALSE !== $key2) {
                        $producto->maximo += $excepcionales[$key2]->cantidad;
                    }

                    $cantidad_actual = Pedido::where([['almacene_id', '=', $request->almacene_id], ['oficina_id', '=', $request->oficina_id], ['estado', '!=', 4]])
                        ->whereDate('fecha', '<=', Carbon::today()->toDateString())
                        ->whereDate('fecha', '>', Carbon::today()->subDays($producto->frecuencia)->toDateString())
                        ->with('productos')
                        ->get()
                        ->pluck('productos')
                        ->flatten()
                        ->where('id', $producto->id)
                        ->where('pivot.estado', '!=', 2)
                        ->sum('pivot.cantidad');

                    if ($producto->maximo != 0 && $producto->maximo > $cantidad_actual) {
                        array_push($respuesta, $producto);
                    }
                }

                return $respuesta;

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
                return $producto->load('tratos.oficina', 'movimientos', 'subcategoria.categoria', 'excepcionales.oficina');
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
