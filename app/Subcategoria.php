<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{

    protected $fillable = [
        'nombre',
        'categoria_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function Categoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id');
    }

}
