<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conexion extends Model
{
    use HasFactory;

    public function ruta_id(){
        return $this->belongsTo(ruta::class, 'ruta_id');
    }

    public function nodo_a(){
        return $this->belongsTo(ubicacion::class, 'ubicacions_id');
    }

    //* relacion de la base de datos de los nodos de ubicacion
    public function nodo_b(){
        return $this->belongsTo(ubicacion::class, 'ubicacions_id');
    }

    protected $fillable = [
        'peso',
        'ruta',
        'nodo_a',
        'nodo_b',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
