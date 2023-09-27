<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ubicacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'posicionx',
        'posiciony',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
