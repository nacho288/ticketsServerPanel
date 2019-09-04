<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'minimo',
        'maximo',
        'stock',
        'alerta'
    ];

    public function tratos()
    {
        return $this->hasMany(Trato::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

}
