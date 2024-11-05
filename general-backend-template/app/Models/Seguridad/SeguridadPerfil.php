<?php

namespace App\Models\Seguridad;

use App\Helpers\EncryptHelper;
use App\Models\Auth\AuthUsuarioPerfil;
use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadPerfil extends Model
{
  use HasFactory, SoftDeletes;

  public $timestamps = false;

  protected $table = 'seguridad_perfil';

  protected $fillable = [
    'nombre',
    'acronimo',
    'id_estado',
  ];

  protected $hidden = [
    'id',
    'deleted_at',
    'privot',
  ];

  protected $appends = [
    'id_encriptado'
  ];

  public function getIdEncriptadoAttribute()
  {
    return EncryptHelper::encrypt($this->attributes['id']);
  }

  public function permisos()
  {
    return $this->belongsToMany(
      SeguridadPermiso::class,
      'seguridad_perfil_permiso',
      'id_perfil',
      'id_permiso',
    )->as('perfil_permisos')->withPivot('id')->with('modulo');
  }

  public function menuopciones()
  {
    return $this->belongsToMany(
      SeguridadPerfilMenuOpcion::class,
      'seguridad_perfil_menu_opcion',
      'id_perfil',
      'id_menu_opcion',
    )->as('perfil_menu_opciones');
  }

  public function estado()
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  public function seguridadPerfilPermisos()
  {
    return $this->hasMany(SeguridadPerfilPermiso::class, 'id_perfil');
  }

  public function usuarioPerfiles()
  {
    return $this->hasMany(AuthUsuarioPerfil::class, 'id_perfil');
  }
}
