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
        'alerta',
        'subcategoria_id'
    ];

    public function tratos()
    {
        return $this->hasMany(Trato::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function subcategoria()
    {
        return $this->hasOne(Subcategoria::class, 'id', 'subcategoria_id');
    }

}
