<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguridadMenuOpcionSeeder extends Seeder
{
  public function run()
  {
    DB::table('seguridad_menu_opcion')->insert([
      [
        'id' => 1,
        'nombre' => 'Dashboard',
        'nombre_unico' => 'dashboard',
        'icono' => 'home',
        'uri' => '/dashboard',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => false,
        'id_menu_opcion_padre' => null,
        'componente' => '../views/dashboard/DashboardView.vue',
      ],
      [
        'id' => 2,
        'nombre' => 'Administración',
        'nombre_unico' => 'administración',
        'icono' => 'admin_panel_settings',
        'uri' => '',
        'enrutable' => false,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => false,
        'id_menu_opcion_padre' => null,
        'componente' => null,
      ],
      [
        'id' => 3,
        'nombre' => 'Gestión de usuarios',
        'nombre_unico' => 'usuarios',
        'icono' => 'people',
        'uri' => '/usuarios',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => true,
        'id_menu_opcion_padre' => 2,
        'componente' => '../views/usuarios/index.vue',
      ],
      [
        'id' => 4,
        'nombre' => 'Permisos',
        'nombre_unico' => 'permisos',
        'icono' => 'list',
        'uri' => '/permisos',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => true,
        'id_menu_opcion_padre' => 2,
        'componente' => '../views/permisos/index.vue',
      ],
      [
        'id' => 5,
        'nombre' => 'Perfiles',
        'nombre_unico' => 'perfiles',
        'icono' => 'user_attributes',
        'uri' => '/perfiles',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => true,
        'id_menu_opcion_padre' => 2,
        'componente' => '../views/perfiles/index.vue',
      ],
      [
        'id' => 6,
        'nombre' => 'Roles',
        'nombre_unico' => 'roles',
        'icono' => 'list',
        'uri' => '/roles',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => true,
        'id_menu_opcion_padre' => 2,
        'componente' => '../views/roles/index.vue',
      ],
      [
        'id' => 7,
        'nombre' => 'Rutas',
        'nombre_unico' => 'rutas',
        'icono' => 'list',
        'uri' => '/configuracion/rutas',
        'enrutable' => true,
        'mostrar_menu' => true,
        'requiere_autenticacion' => true,
        'id_modulo' => 1,
        'id_estado' => 1,
        'dependencia_menu_padre' => true,
        'id_menu_opcion_padre' => 2,
        'componente' => '../views/rutas/index.vue',
      ],
    ]);
  }
}
