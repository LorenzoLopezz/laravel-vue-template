<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\SeguridadRolController;
use App\Http\Controllers\SeguridadModuloController;
use App\Http\Controllers\SeguridadPermisoController;
use App\Http\Controllers\SeguridadPerfilController;
use App\Http\Controllers\AuthUsuarioController;
use App\Http\Controllers\SeguridadMenuOpcionController;

Route::prefix('auth')->group(function () {
  Route::get('/cuenta', [AuthUsuarioController::class, 'informacion']);
  Route::get('/menu', [AuthUsuarioController::class, 'menu']);
  Route::get('/permisos', [AuthUsuarioController::class, 'permisos']);
  Route::post('/cerrar-sesion', [AuthController::class, 'cerrarSesion']);
});

Route::prefix('admin')->group(function () {
  // MODULOS
  Route::get('/modulos', [SeguridadModuloController::class, 'listar'])->middleware(['check.permissions:LISTAR_MODULO']);
  Route::post('/modulos', [SeguridadModuloController::class, 'crear'])->middleware(['check.permissions:CREAR_MODULO']);
  Route::get('/modulos/{id}', [SeguridadModuloController::class, 'ver'])->middleware(['check.permissions:VER_MODULO']);
  Route::put('/modulos/{id}', [SeguridadModuloController::class, 'actualizar'])->middleware(['check.permissions:EDITAR_MODULO']);
  Route::put('/modulos/{id}/estado/{id_estado}', [SeguridadModuloController::class, 'cambiarEstado'])->middleware(['check.permissions:EDITAR_ESTADO_MODULO']);

  // PERMISOS
  Route::get('/permisos', [SeguridadPermisoController::class, 'listar'])->middleware(['check.permissions:LISTAR_PERMISO']);
  Route::post('/permisos', [SeguridadPermisoController::class, 'crear'])->middleware(['check.permissions:CREAR_PERMISO']);
  Route::get('/permisos/{id}', [SeguridadPermisoController::class, 'ver'])->middleware(['check.permissions:VER_PERMISO']);
  Route::put('/permisos/{id}', [SeguridadPermisoController::class, 'actualizar'])->middleware(['check.permissions:EDITAR_PERMISO']);
  Route::delete('/permisos/{id}', [SeguridadPermisoController::class, 'eliminar'])->middleware(['check.permissions:ELIMINAR_PERMISO']);

  // PERFILES
  Route::get('/perfiles', [SeguridadPerfilController::class, 'listar'])->middleware(['check.permissions:LISTAR_PERFIL']);
  Route::post('/perfiles', [SeguridadPerfilController::class, 'crear'])->middleware(['check.permissions:CREAR_PERFIL']);
  Route::get('/perfiles/{id}', [SeguridadPerfilController::class, 'ver'])->middleware(['check.permissions:VER_PERFIL']);
  Route::put('/perfiles/{id}', [SeguridadPerfilController::class, 'actualizar'])->middleware(['check.permissions:EDITAR_PERFIL']);
  Route::put('/perfiles/{id}/estado/{id_estado}', [SeguridadPerfilController::class, 'cambiarEstado'])->middleware(['check.permissions:EDITAR_ESTADO_PERFIL']);
  Route::post('/perfiles/{id}/permisos', [SeguridadPerfilController::class, 'agregarPermiso'])->middleware(['check.permissions:AGREGAR_PERMISO_PERFIL']);
  Route::get('/perfiles/{id}/permisos', [SeguridadPerfilController::class, 'permisos'])->middleware(['check.permissions:LISTAR_PERMISO_PERFIL']);
  Route::delete('/perfiles/{id}/permisos', [SeguridadPerfilController::class, 'eliminarPermiso'])->middleware(['check.permissions:ELIMINAR_PERMISO_PERFIL']);

  // USUARIOS
  Route::get('/usuarios', [AuthUsuarioController::class, 'listar'])->middleware(['check.permissions:LISTAR_USUARIO']);
  Route::post('/usuarios', [AuthUsuarioController::class, 'crear'])->middleware(['check.permissions:CREAR_USUARIO']);
  Route::get('/usuarios/info', [AuthUsuarioController::class, 'informacion']);

  Route::get('/usuarios/{id}', [AuthUsuarioController::class, 'ver'])->middleware(['check.permissions:VER_USUARIO']);
  Route::put('/usuarios/{id}', [AuthUsuarioController::class, 'editar'])->middleware(['check.permissions:EDITAR_USUARIO']);
  Route::put('/usuarios/{id}/password', [AuthUsuarioController::class, 'editarPassword'])->middleware(['check.permissions:EDITAR_USUARIO_PASSWORD']);
  Route::post('/usuarios/{idUsuario}/bloqueo', [AuthUsuarioController::class, 'bloquearUsuario'])->middleware(['check.permissions:BLOQUEAR_USUARIO']);

  // OPCIONES DE MENU
  Route::get('/menu', [SeguridadMenuOpcionController::class, 'listar'])->middleware(['check.permissions:LISTAR_RUTAS']);
  Route::post('/menu', [SeguridadMenuOpcionController::class, 'crear'])->middleware(['check.permissions:CREAR_RUTA']);
  Route::get('/menu/{id}', [SeguridadMenuOpcionController::class, 'ver'])->middleware(['check.permissions:OBTENER_RUTA']);
  Route::put('/menu/{id}', [SeguridadMenuOpcionController::class, 'actualizar'])->middleware(['check.permissions:EDITAR_MENU_OPCION']);
  Route::put('/menu/{id}/estado', [SeguridadMenuOpcionController::class, 'cambiarEstado'])->middleware(['check.permissions:EDITAR_ESTADO_MENU_OPCION']);
  Route::put('/menu/{id}/mostrar-menu', [SeguridadMenuOpcionController::class, 'cambiarMostrarMenu'])->middleware(['check.permissions:EDITAR_MOSTRAR_MENU']);

  // ROLES
  Route::get('/roles', [SeguridadRolController::class, 'listar'])->middleware(['check.permissions:LISTAR_ROLES']);
  Route::post('/roles', [SeguridadRolController::class, 'crear'])->middleware(['check.permissions:CREAR_ROL']);
  Route::get('/roles/{id}', [SeguridadRolController::class, 'ver'])->middleware(['check.permissions:VER_ROL']);
  Route::put('/roles/{id}', [SeguridadRolController::class, 'actualizar'])->middleware(['check.permissions:EDITAR_ROL']);
  Route::put('/roles/{id}/estado/{id_estado}', [SeguridadRolController::class, 'cambiarEstado'])->middleware(['check.permissions:EDITAR_ESTADO_ROL']);
  Route::post('/roles/{id}/permisos', [SeguridadRolController::class, 'agregarPermiso'])->middleware(['check.permissions:AGREGAR_PERMISO_ROL']);
  Route::get('/roles/{id}/permisos', [SeguridadRolController::class, 'permisos'])->middleware(['check.permissions:LISTAR_PERMISO_ROL']);
  Route::delete('/roles/{id}/permisos', [SeguridadRolController::class, 'eliminarPermiso'])->middleware(['check.permissions:ELIMINAR_PERMISO_ROL']);
  Route::delete('/roles/{id}', [SeguridadRolController::class, 'eliminarRol'])->middleware(['check.permissions:ELIMINAR_ROL']);
});
