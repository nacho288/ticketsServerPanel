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


    public function usuario()
    {
        return $this->hasOne('App\Usuario', 'id', 'oficina_id');
    }

}
