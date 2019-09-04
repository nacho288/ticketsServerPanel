<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'usuario_id',
        'producto_id',
        'original',
        'nuevo',
        'fecha',
        'tipo_id'
    ];

}
