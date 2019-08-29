<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'minimo',
        'maximo'
    ];

    public function tratos()
    {
        return $this->hasMany(Trato::class);
    }

}
