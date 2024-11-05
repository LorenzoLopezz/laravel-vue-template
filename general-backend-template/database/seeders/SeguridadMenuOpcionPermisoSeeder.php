<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadMenuOpcionPermisoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Configuración inicial del perfil con las opciones de menú
    DB::table('seguridad_menu_opcion_permiso')->insert([
      ['id_permiso' => 1, 'id_menu_opcion' => 1],
      ['id_permiso' => 1, 'id_menu_opcion' => 3],
      ['id_permiso' => 1, 'id_menu_opcion' => 4],
      ['id_permiso' => 1, 'id_menu_opcion' => 5],
      ['id_permiso' => 1, 'id_menu_opcion' => 6],
      ['id_permiso' => 1, 'id_menu_opcion' => 7],
    ]);
  }
}
