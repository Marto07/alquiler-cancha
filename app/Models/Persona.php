<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //
    use HasFactory;

    protected $table = 'persona';
    protected $fillable = ['nombre', 'apellido', 'fecha_nacimiento', 'activo'];

    public function documentos() 
    {
        return $this->hasMany(Documento::class, 'rela_persona');
    }

    public function contactos() 
    {
        return $this->hasMany(Contacto::class, 'rela_persona');
    }
}
