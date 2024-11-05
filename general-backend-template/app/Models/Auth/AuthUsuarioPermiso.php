<?php

namespace App\Models\Auth;

use App\Models\Seguridad\SeguridadPermiso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthUsuarioPermiso extends Model
{
  use HasFactory;

  protected $table = 'auth_usuario_permiso';

  protected $fillable = [
    'id_usuario',
    'id_permiso',
  ];

  protected $hidden = [
    'id_usuario',
    'id_permiso'
  ];

  public function usuario()
  {
    return $this->belongsTo(AuthUsuario::class, 'id_usuario', 'id');
  }

  public function permiso()
  {
    return $this->belongsTo(SeguridadPermiso::class, 'id_permiso', 'id');
  }
}
