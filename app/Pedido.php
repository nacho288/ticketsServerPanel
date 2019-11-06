<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot(['cantidad', 'estado']);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function evaluador()
    {
        return $this->hasOne(User::class, 'id', 'evaluado_por');
    }

    public function retirador()
    {
        return $this->hasOne(User::class, 'id', 'retirado_por');
    }

    public function entregador()
    {
        return $this->hasOne(User::class, 'id', 'entregado_por');
    }

    public function almacene()
    {
        return $this->hasOne(Almacene::class, 'id', 'almacene_id');
    }

    public function oficina()
    {
        return $this->hasOne(Oficina::class, 'id', 'oficina_id');
    }


}
