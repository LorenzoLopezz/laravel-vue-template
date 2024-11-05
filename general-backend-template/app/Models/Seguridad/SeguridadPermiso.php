<?php

namespace App\Models\Seguridad;

use App\Models\Auth\AuthUsuarioPermiso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadPermiso extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'seguridad_permiso';
  public $timestamps = false;

  protected $fillable = [
    'id_modulo',
    'nombre',
    'descripcion',
  ];

  protected $hidden = [
    'pivot'
  ];

  public function modulo()
  {
    return $this->belongsTo(SeguridadModulo::class, 'id_modulo', 'id');
  }

  public function perfiles()
  {
    return $this->belongsToMany(
      SeguridadPerfil::class,
      'seguridad_perfil_permiso',
      'id_permiso',
      'id_perfil',
    )->as('perfil_permisos');
  }

  public function perfilPermisos()
  {
    return $this->hasMany(SeguridadPerfilPermiso::class, 'id_permiso');
  }

  public function menuOpcionPermisos()
  {
    return $this->hasMany(SeguridadMenuOpcionPermiso::class, 'id_permiso');
  }

  public function usuarioPermisos()
  {
    return $this->hasMany(AuthUsuarioPermiso::class, 'id_permiso');
  }
}
