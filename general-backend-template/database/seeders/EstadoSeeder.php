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
      ['id' => 1, 'nombre' => 'Activo', 'id_calificador' => 1],
      ['id' => 2, 'nombre' => 'Inactivo', 'id_calificador' => 1],
      ['id' => 3, 'nombre' => 'Habilitado', 'id_calificador' => 1],
      ['id' => 4, 'nombre' => 'Deshabilitado', 'id_calificador' => 1],
      ['id' => 5, 'nombre' => 'Suspendido', 'id_calificador' => 1],
      ['id' => 6, 'nombre' => 'Archivado', 'id_calificador' => 1],
      ['id' => 7, 'nombre' => 'En proceso', 'id_calificador' => 1],
      ['id' => 8, 'nombre' => 'Finalizado', 'id_calificador' => 1],
      ['id' => 9, 'nombre' => 'En Pausa', 'id_calificador' => 1],
      ['id' => 10, 'nombre' => 'Abortado', 'id_calificador' => 1],
      ['id' => 11, 'nombre' => 'Pendiente', 'id_calificador' => 1],
      ['id' => 12, 'nombre' => 'Cancelado', 'id_calificador' => 1],
      ['id' => 13, 'nombre' => 'Asignado', 'id_calificador' => 1],
      ['id' => 14, 'nombre' => 'Bloqueado', 'id_calificador' => 1],
    ]);
    DB::statement('ALTER SEQUENCE estado_id_seq RESTART WITH 15');
  }
}
