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

    protected $fillable = [
        'nodo_inicial',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
