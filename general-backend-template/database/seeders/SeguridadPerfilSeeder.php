<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadPerfilSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('seguridad_perfil')->insert([
      [
        'id'        => 1,
        'nombre'    => 'Super administrador',
        'acronimo'  => 'SUPER_ADMIN',
        'id_estado' => 1,
      ],
    ]);

    DB::statement('ALTER SEQUENCE seguridad_perfil_id_seq RESTART WITH 2');
  }
}
