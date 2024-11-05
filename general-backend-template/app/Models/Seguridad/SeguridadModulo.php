<?php

namespace App\Models\Seguridad;

use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadModulo extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'seguridad_modulo';

  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'acronimo',
    'id_estado',
  ];

  public function permisos()
  {
    return $this->hasMany(SeguridadPermiso::class, 'id_modulo')->as('permisos');
  }

  public function estado()
  {
    return $this->belongsTo(Estado::class, 'id_estado ');
  }

  public function seguridadMenus()
  {
    return $this->hasMany(SeguridadMenuOpcion::class, 'id_modulo');
  }
}
