<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeguridadPerfilMenuOpcion extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'seguridad_perfil_menu_opcion';

  public $timestamps = false;

  protected $fillable = ['id_perfil', 'id_menu_opcion'];

  public function perfil()
  {
    return $this->belongsTo(SeguridadPerfil::class, 'id_perfil', 'id');
  }

  public function menu_opcion()
  {
    return $this->belongsTo(SeguridadMenuOpcion::class, 'id_menu_opcion', 'id');
  }
}
