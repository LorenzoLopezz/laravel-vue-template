<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadMenuOpcion extends Model
{
  use SoftDeletes, HasFactory;

  protected $table = 'seguridad_menu_opcion';

  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'nombre_unico',
    'icono',
    'uri',
    'componente',
    'id_menu_opcion_padre',
    'id_modulo',
    'mostrar_menu',
    'requiere_autenticacion',
    'enrutable',
    'dependencia_menu_padre',
    'id_estado',
  ];

  protected $hidden = [
    'deleted_at',
    'id_estado'
  ];

  public function perfiles()
  {
    return $this->belongsToMany(
      SeguridadPerfil::class,
      'seguridad_perfil_menu_opcion',
      'id_perfil',
      'id_menu_opcion',
    )->as('perfil_menu_opciones');
  }

  public function permisos()
  {
    return $this->belongsToMany(
      SeguridadPermiso::class,
      'seguridad_menu_opcion_permiso',
      'id_menu_opcion',
      'id_permiso',
    )->as('menu_opcion_permiso');
  }

  public function parent()
  {
    return $this->belongsTo(SeguridadMenuOpcion::class, 'id_menu_opcion_padre', 'id');
  }

  public function children()
  {
    return $this->hasMany(SeguridadMenuOpcion::class, 'id_menu_opcion_padre', 'id');
  }

  public function modulo()
  {
    return $this->belongsTo(SeguridadModulo::class, 'id_modulo');
  }
}
