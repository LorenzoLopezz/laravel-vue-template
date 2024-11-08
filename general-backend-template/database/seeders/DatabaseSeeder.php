<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      EstadoCalificadorSeeder::class,
      EstadoSeeder::class,
      SeguridadModuloSeeder::class,
      SeguridadPermisoSeeder::class,
      SeguridadPerfilSeeder::class,
      SeguridadPerfilPermisoSeeder::class,
      AuthUsuarioSeeder::class,
      AuthUsuarioPerfilSeeder::class,
      SeguridadMenuOpcionSeeder::class,
      SeguridadMenuOpcionPermisoSeeder::class,
    ]);
  }
}
