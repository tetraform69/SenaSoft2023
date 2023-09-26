<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class conexion extends Model
{
    use HasFactory;

    public function nodo_a(){
        return $this->belongsTo(User::class, 'ubicacions_id');
    }

    //* relacion de la base de datos de los nodos de ubicacion
    public function nodo_b(){
        return $this->belongsTo(User::class, 'ubicacions_id');
    }
}
