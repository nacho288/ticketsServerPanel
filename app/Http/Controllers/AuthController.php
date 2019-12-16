<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'username'    => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
            'type' => 'required',
        ]);
        $user = new User([
            'name'     => $request->name,
            'username'    => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type,
        ]);

        try {
            $user->save();
            return ["error" => false];
        } catch (Exception $e) {
            return ["error" => true];
        }

    }
    public function login(Request $request)
    {
        $request->validate([
            'username'       => 'required|string',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => true
            ]);
        }
        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )
                ->toDateTimeString(),
            'user' => User::with('oficinas.almacenes', 'almacenes.oficinas.usuarios', 'almacenes.administradores')->where('id', $request->user()->id)->first()
        ]);
    }

    public function reset(Request $request)
    {
        if ($request->has('super') && $request->user()->type == 9 && $request->user()->id != $request->userMod_id ) {
            try {
                $aModificar = User::where('id', $request->userMod_id)->first();
                $aModificar->password = bcrypt($request->password);
                $aModificar->save();
                return ["error" => false];
            } catch (Exception $e) {
                return ["error" => true];
            }
        }

        try {

            $credentials = ['username' => $request->user()->username, 'password' => $request->original];

            if (!Auth::guard('web')->attempt($credentials, false, false)) {
                return response()->json([
                    'error' => true,
                    'why' => $credentials
                ]);
            }

            $request->user()->password = bcrypt($request->password);
            $request->user()->save();
            $request->user()->token()->revoke();

            return ["error" => false];

        } catch (Exception $e) {
            return ["error" => true];
        }

        return ["error" => true];
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['logout' =>
        true]);
    }
    public function user(Request $request)
    {
        if ($request->user()->type == 0) {
            return User::with('oficinas.almacenes')->where('id', $request->user()->id)->first();
        }

        if ($request->user()->type == 1) {
            return User::with('almacenes.oficinas')->where('id', $request->user()->id)->first();
        }
        
        return response()->json($request->user());
    }
}
