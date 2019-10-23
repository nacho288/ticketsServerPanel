<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'nombre', 'almacene_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class)->orderBy('nombre');
    }
}
