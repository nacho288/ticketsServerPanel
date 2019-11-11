<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trato extends Model
{

    protected $fillable = [
        'producto_id',
        'oficina_id',
        'minimo',
        'maximo'
    ];

    public function oficina()
    {
        return $this->hasOne(Oficina::class, 'id', 'oficina_id');
    }

}
