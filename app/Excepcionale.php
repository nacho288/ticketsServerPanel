<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excepcionale extends Model
{
    protected $fillable = [
        'producto_id',
        'oficina_id',
        'cantidad',
        'inicio',
        'final'
    ];

    public function oficina()
    {
        return $this->hasOne(Oficina::class, 'id', 'oficina_id');
    }
}
