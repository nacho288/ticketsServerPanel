<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'minimo',
        'maximo',
        'stock',
        'alerta',
        'subcategoria_id',
        'almacene_id',
        'frecuencia',
        'preparacion'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function tratos()
    {
        return $this->hasMany(Trato::class);
    }

    public function excepcionales()
    {
        return $this->hasMany(Excepcionale::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class)->orderBy('id', 'desc');
    }

    public function subcategoria()
    {
        return $this->hasOne(Subcategoria::class, 'id', 'subcategoria_id');
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class)->withPivot(['cantidad', 'estado']);
    }

}
