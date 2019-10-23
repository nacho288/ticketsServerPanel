<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'user_id',
        'oficina_id',
        'producto_id',
        'original',
        'nuevo',
        'fecha',
        'tipo',
        'comentario'
    ];

}
