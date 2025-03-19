<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    //
    protected $table = 'usuario';

    protected $fillable = [
        'username', 'password', 'rela_contacto', 'rela_rol', 
        'verificacion', 'token', 'token_expira','activo'
    ];

    protected $hidden = ['password', 'token', 'token_expira']; // Ocultamos password y tokens

    public function contacto() 
    {
        return $this->belongsTo(Contacto::class, 'rela_contacto');
    }

    public function rol() 
    {
        return $this->belongsTo(Rol::class, 'rela_rol');
    }

    public function getEmailAttribute() 
    {
        return $this->contacto ? $this->contacto->descripcion : null;
    }

    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
