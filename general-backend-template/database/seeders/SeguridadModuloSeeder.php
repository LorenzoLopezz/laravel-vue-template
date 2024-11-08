<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadModuloSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Modulo inicial
    DB::table('seguridad_modulo')->insert([
      [
        'id' => 1,
        'nombre' => 'Administración',
        'acronimo' => 'ADMIN',
        'id_estado' => 1,
      ],
    ]);

    DB::statement('ALTER SEQUENCE seguridad_modulo_id_seq RESTART WITH 2');
  }
}
