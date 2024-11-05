<?php

namespace App\Models\Seguridad;

use App\Models\Proyecto\ProyectoParticipante;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadRol extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $table = 'seguridad_rol';

  protected $fillable = [
    'nombre',

  ];

  protected $guarded = [
    'id'
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',

  ];

  public function seguridadRolPermiso()
  {
    return $this->hasMany(SeguridadRolPermiso::class, 'id_rol');
  }

  public function proyectoParticipante()
  {
    return $this->hasMany(ProyectoParticipante::class, 'id_proyecto_rol');
  }

  public function permisos()
  {
    return $this->belongsToMany(
      SeguridadPermiso::class,
      'seguridad_rol_permiso',
      'id_seguridad_permiso',
      'id_rol',
    )->as('rol_permisos')->withPivot('id')->with('modulo');
  }
}
