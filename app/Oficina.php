<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class);
    }

    public function almacenes()
    {
        return $this->belongsToMany(Almacene::class);
    }
}
