<?php

namespace App\Models\Auth;

use App\Models\Seguridad\SeguridadPerfil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthUsuarioPerfil extends Model
{
  use HasFactory;

  protected $table = 'auth_usuario_perfil';

  protected $fillable = [
    'id_usuario',
    'id_perfil',
    'creado_por',
  ];

  protected $hidden = [
    'id_usuario',
    'id_perfil',
  ];

  public function usuario()
  {
    return $this->belongsTo(AuthUsuario::class, 'id_usuario', 'id');
  }

  public function perfil()
  {
    return $this->belongsTo(SeguridadPerfil::class, 'id_perfil', 'id');
  }
}
