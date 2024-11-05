<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeguridadMenuOpcionPermiso extends Model
{
  use HasFactory;
  protected $table = 'seguridad_menu_opcion_permiso';

  public $timestamps = true;

  protected $fillable = ['id_permiso', 'id_menu_opcion'];

  public function permiso()
  {
    return $this->belongsTo(SeguridadPermiso::class, 'id_permiso', 'id');
  }

  public function menu_opcion()
  {
    return $this->belongsTo(SeguridadMenuOpcion::class, 'id_menu_opcion', 'id');
  }
}
