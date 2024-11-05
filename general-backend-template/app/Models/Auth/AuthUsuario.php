<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubject;
use App\Models\Seguridad\SeguridadPerfil;
use App\Models\Estado;
use App\Models\Seguridad\SeguridadPermiso;

class AuthUsuario extends Authenticatable implements JWTSubject
{

  use Notifiable, SoftDeletes;

  protected $table = 'auth_usuario';
  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'username',
    'codigo',
    'email',
    'password',
    'verificado',
    'fecha_verificacion',
    'id_estado',
    'creado_por',
    'ultima_sesion',
    'created_at',
    'updated_at',
    'primer_nombre',
    'segundo_nombre',
    'primer_apellido',
    'segundo_apellido',
    'fecha_nacimiento',
    'creado_por',
    'justificacion_bloqueo',
    'remember_token'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'updated_at',
    'deleted_at',
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'fecha_verificacion' => 'datetime',
    'ultima_sesion' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function sesiones()
  {
    return $this->hasMany(AuthUsuarioSesion::class, 'id_usuario');
  }

  public function perfiles()
  {
    return $this->belongsToMany(
      SeguridadPerfil::class,
      'auth_usuario_perfil',
      'id_usuario',
      'id_perfil'
    )->select('seguridad_perfil.id', 'seguridad_perfil.nombre', 'seguridad_perfil.acronimo');
  }

  public function getSesionActiva()
  {
    return $this->sesiones()->where('id_estado', '=', 1)->first();
  }

  public function estado()
  {
    return $this->belongsTo(Estado::class, 'id_estado');
  }

  public function creador()
  {
    return $this->belongsTo(AuthUsuario::class, 'creado_por');
  }

  public function permisos()
  {
    return $this->belongsToMany(
      SeguridadPermiso::class,
      'auth_usuario_permiso',
      'id_usuario',
      'id_permiso'
    );
  }

  public function getPermisos()
  {
    $permisosPerfil = SeguridadPermiso::join('seguridad_perfil_permiso as spp', 'seguridad_permiso.id', '=', 'spp.id_permiso')
      ->select('seguridad_permiso.nombre')
      ->whereIn('spp.id_perfil', $this->perfiles->pluck('id'))
      ->get();

    $permisos = $permisosPerfil;

    if (count($this->permisos) > 0) {
      $permisos->merge($this->permisos);
    }

    return $permisos;
  }

  public function poseePermisos($permisos)
  {
    return $this->getPermisos()->pluck('nombre')->intersect($permisos)->count() === count($permisos);
  }

  public function esAdministrador()
  {
    return $this->perfiles->pluck('acronimo')->contains('SUPER_ADMIN');
  }

  public function usuarioPerfiles()
  {
    return $this->hasMany(AuthUsuarioPerfil::class, 'id_usuario');
  }

  public function usuarioPermisos()
  {
    return $this->hasMany(AuthUsuarioPermiso::class, 'id_usuario');
  }
}
