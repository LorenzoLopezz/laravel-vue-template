<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthUsuarioPerfilSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('auth_usuario_perfil')->insert([
      'id'          => 1,
      'id_usuario'  => 1,
      'id_perfil'   => 1,
      'creado_por'  => 1,
    ]);
    DB::statement('ALTER SEQUENCE auth_usuario_perfil_id_seq RESTART WITH 2');
  }
}
