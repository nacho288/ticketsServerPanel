<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad');
    }

    public function usuario()
    {
        return $this->hasOne('App\Usuario', 'id', 'usuario_id');
    }

    public function aprovadoPor()
    {
        return $this->hasOne('App\Usuario', 'id', 'aprovado_por');
    }

}
