<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;


//class Administrador extends Model
class Administrador extends Authenticatable
{
    use Notifiable;

    protected $table = 'administradores';

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'usuario',
        'contraseña',
        'imagen',
        'rol_id',
        'activo',
        'google_id'
    ];

      protected $hidden = [
         'contraseña',
     ];

    public function getAuthPassword()
    {
        return $this->contraseña;
    }
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}
