<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    // Si quieres permitir guardar campos específicos, agrega:
    protected $fillable = [
        'user_id',
        'categoria_id',
        'proveedor_id',
        'codigo',
        'nombre',
        'descripcion',
        'precio_venta',
        'precio_compra',
        'cantidad',
        'activo'
    ];

    // Si tu tabla usa timestamps (created_at, updated_at), déjalo así;
    // si no los usa, agrega esto:
    // public $timestamps = false;
}
