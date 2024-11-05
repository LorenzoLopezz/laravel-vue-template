<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadRolPermiso extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $table = 'seguridad_rol_permiso';

  protected $fillable = [
    'id_seguridad_permiso',
    'id_rol',
  ];

  protected $guarded = [
    'id'
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',

  ];

  public function seguridadPermiso()
  {
    return $this->belongsTo(SeguridadPerfilPermiso::class, 'id_seguridad_permiso', 'id');
  }

  public function rol()
  {
    return $this->belongsTo(SeguridadRol::class, 'id_rol', ' id');
  }
}
