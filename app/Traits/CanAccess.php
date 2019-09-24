<?php

namespace App\Traits;

use App\Almacene;
use App\Oficina;

trait CanAccess
{
    protected function CanUserAlmacen($user, $oficina_id, $almacene_id)
    {
        if ($user->type == 0) {
            $alamacen = Almacene::where('id', $almacene_id)
                ->with('oficinas.usuarios');

            if (!$alamacen->exists()) {
                return false;
            }

            $oficina = $alamacen->first()->oficinas->where('id', $oficina_id);

            if ($oficina->count() == 0) {
                return false;
            }

            $hasUsuario = $oficina->first()->usuarios->where('id', $user->id);

            if ($hasUsuario->count() == 0) {
                return false;
            }

            return true;

        }
        return false;
    }

    protected function CanAdminAlmacen($user, $almacene_id)
    {
        if ($user->type == 1) {
            return $user->almacenes->where('id', $almacene_id)->count() > 0;
        }
        return false;
    }

    protected function CanUserOficina($user, $oficina_id)
    {
        if ($user->type == 0) {
            $oficina = Oficina::where('id', $oficina_id)
                ->with('usuarios');

            if (!$oficina->exists()) {
                return false;
            }

            $hasUsuario = $oficina->first()->usuarios->where('id', $user->id);

            if ($hasUsuario->count() == 0) {
                return false;
            }

            return true;
        }
        return false;
    }
}
