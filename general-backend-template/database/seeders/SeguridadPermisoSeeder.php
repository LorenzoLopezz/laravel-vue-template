<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadPermisoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Permisos del modulo Administracion
    DB::table('seguridad_permiso')->insert([
      [
        'id_modulo' => 1,
        'nombre' => 'ADMIN_SUPER_USER',
        'descripcion' => 'Super administrador',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_DASHBOARD',
        'descripcion' => 'Mostrar pantalla de inicio',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'LISTAR_MODULO',
        'descripcion' => 'Listar módulos',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'CREAR_MODULO',
        'descripcion' => 'Ver módulo',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_MODULO',
        'descripcion' => 'Ver módulo',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_MODULO',
        'descripcion' => 'Editar módulo',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_ESTADO_MODULO',
        'descripcion' => 'Editar estado de módulo',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'LISTAR_PERMISO',
        'descripcion' => 'Listar permisos',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'CREAR_PERMISO',
        'descripcion' => 'Crear permiso',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_PERMISO',
        'descripcion' => 'Ver permiso',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_PERMISO',
        'descripcion' => 'Editar permiso',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'ELIMINAR_PERMISO',
        'descripcion' => 'Eliminar permiso',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'LISTAR_PERFIL',
        'descripcion' => 'Listar perfiles',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'CREAR_PERFIL',
        'descripcion' => 'Crear perfil',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_PERFIL',
        'descripcion' => 'Ver perfil',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_PERFIL',
        'descripcion' => 'Editar perfil',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_ESTADO_PERFIL',
        'descripcion' => 'Editar estado de perfil',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'LISTAR_USUARIO',
        'descripcion' => 'Listar usuarios',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'CREAR_USUARIO',
        'descripcion' => 'Crear usuario',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_USUARIO',
        'descripcion' => 'Ver usuario',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_USUARIO',
        'descripcion' => 'Editar usuario',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_USUARIO_PASSWORD',
        'descripcion' => 'Editar password de usuario',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'LISTAR_MENU_OPCION',
        'descripcion' => 'Listar opciones de menu',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'CREAR_MENU_OPCION',
        'descripcion' => 'Crear opción de menu',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'VER_MENU_OPCION',
        'descripcion' => 'Ver opción de menu',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_MENU_OPCION',
        'descripcion' => 'Editar opción de menu',
      ],
      [
        'id_modulo' => 1,
        'nombre' => 'EDITAR_ESTADO_MENU_OPCION',
        'descripcion' => 'Editar estado de opción de menu',
      ],
    ]);
  }
}
