<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Exception;

class ConectarController extends Controller
{

    public function conectar(Request $request){

        try{
            $usuario = Usuario::where('nombre_usuario', $request->usuario)->firstOrFail();
        }
        catch (Exception $e){
            return[ 'error' => true,
                    'errorType' => 0];
        }

        if ($request->contrasena == $usuario->contrasena){
            $usuario->error = false;
            return $usuario;
        }
        else {
            return [
                'error' => true,
                'errorType' => 1
            ];
        }

    }

    


}
