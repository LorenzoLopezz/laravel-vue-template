<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Estados iniciales
    DB::table('estado')->insert([
      ['id' => 1, 'nombre' => 'Activo'],
      ['id' => 2, 'nombre' => 'Inactivo'],
      ['id' => 3, 'nombre' => 'Habilitado'],
      ['id' => 4, 'nombre' => 'Deshabilitado'],
      ['id' => 5, 'nombre' => 'Suspendido'],
      ['id' => 6, 'nombre' => 'Archivado'],
      ['id' => 7, 'nombre' => 'En proceso'],
      ['id' => 8, 'nombre' => 'Finalizado'],
      ['id' => 9, 'nombre' => 'En Pausa'],
      ['id' => 10, 'nombre' => 'Abortado'],
      ['id' => 11, 'nombre' => 'Pendiente'],
      ['id' => 12, 'nombre' => 'Cancelado'],
      ['id' => 13, 'nombre' => 'Asignado'],
      ['id' => 14, 'nombre' => 'Bloqueado'],
    ]);
    DB::statement('ALTER SEQUENCE estado_id_seq RESTART WITH 15');
  }
}
