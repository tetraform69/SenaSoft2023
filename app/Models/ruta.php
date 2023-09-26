<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ruta extends Model
{
    use HasFactory;

    public function conexiones() {
        return $this->hasMany(conexion::class);
    }
    
    public function ubicaciones() {
        return $this->hasMany(ubicacion::class);
    }
}
