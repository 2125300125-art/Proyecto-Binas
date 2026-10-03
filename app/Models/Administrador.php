<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradores';

    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'usuario',
        'contraseña',
        'imagen',
        'rol_id',
        'estado'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}