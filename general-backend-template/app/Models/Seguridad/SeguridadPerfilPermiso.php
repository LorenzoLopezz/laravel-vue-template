<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguridadPerfilPermiso extends Model
{
  use HasFactory;

  protected $table = 'seguridad_perfil_permiso';

  protected $fillable = [
    'id_perfil',
    'id_permiso'
  ];

  public function perfil()
  {
    return $this->belongsTo(SeguridadPerfil::class, 'id_perfil')->as('perfil');
  }

  public function permiso()
  {
    return $this->belongsTo(SeguridadPermiso::class, 'id_permiso')->as('permiso');
  }
}
