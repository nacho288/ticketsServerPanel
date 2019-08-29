<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    protected $fillable = [
        'nombre_usuario',
        'nombre',
        'contrasena',
        'tipo'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function tratos()
    {
        return $this->hasMany(Trato::class);
    }
}
