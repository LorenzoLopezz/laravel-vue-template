<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthUsuarioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Usuuario super admin
    DB::table('auth_usuario')->insert([
      'id'                  => 1,
      'codigo'              => Str::uuid(),
      'username'            => 'administrador',
      'email'               => 'admin@domain.app',
      'password'            => Hash::make('AdminDomainApp'),
      'verificado'          => true,
      'id_estado'           => 1,
      'primer_nombre'       => 'Super',
      'segundo_nombre'      => null,
      'primer_apellido'     => 'Administrador',
      'segundo_apellido'    => null,
      'fecha_nacimiento'    => '1970-01-01',
      'justificacion_bloqueo' => null,
    ]);

    DB::statement('ALTER SEQUENCE auth_usuario_id_seq RESTART WITH 2');
  }
}
