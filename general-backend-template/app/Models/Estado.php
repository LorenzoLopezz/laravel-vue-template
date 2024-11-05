<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\AuthUsuario;
use App\Models\Seguridad\SeguridadModulo;
use App\Models\Seguridad\SeguridadPerfil;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'estado';

  public $timestamps = false;

  protected $fillable = ['nombre'];

  public function usuarios()
  {
    return $this->hasMany(AuthUsuario::class, 'id_estado')->as('usuarios');
  }

  public function modulos()
  {
    return $this->hasMany(SeguridadModulo::class, 'id_estado')->as('modulos');
  }

  public function seguridadperfiles()
  {
    return $this->hasMany(SeguridadPerfil::class, 'id_estado');
  }

  public function seguridadmodulos()
  {
    return $this->hasMany(SeguridadModulo::class, 'id_estado');
  }
}
