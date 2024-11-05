<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadPerfilPermisoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Permiso para el SUPER_ADMIN
    DB::table('seguridad_perfil_permiso')->insert([
      'id_perfil' => 1,
      'id_permiso' => 1,
    ]);
  }
}
