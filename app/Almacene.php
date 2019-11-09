<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacene extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function oficinas()
    {
        return $this->belongsToMany(Oficina::class)->orderBy('nombre');
    }

    public function administradores()
    {
        return $this->belongsToMany(User::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
