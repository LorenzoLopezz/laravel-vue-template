<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthUsuarioSesion extends Model
{
  use HasFactory;

  protected $table = 'auth_usuario_sesion';

  protected $fillable = [
    'id_usuario',
    'token',
    'ip',
    'plataforma',
    'id_estado',
    'expires_at',
    'created_at',
    'updated_at',
    'closed_at',
  ];

  protected $casts = [
    'expires_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'closed_at' => 'datetime',
  ];

  public function usuario()
  {
    return $this->belongsTo(AuthUsuario::class, 'id_usuario', 'id')->findAll();
  }
}
