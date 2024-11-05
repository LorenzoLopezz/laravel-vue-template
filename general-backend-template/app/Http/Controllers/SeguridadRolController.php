<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;
use App\Models\Seguridad\SeguridadRol;
use Illuminate\Support\Facades\Validator;

class SeguridadRolController extends Controller
{
  public function listar()
  {
    $perfiles = SeguridadRol::get();
    $data = $perfiles->map(function ($item) {
      $encryptId = EncryptHelper::encrypt($item->id);
      return [
        'id'       => $encryptId,
        'nombre'   => $item->nombre,
      ];
    });
    return response()->json($data, 200);
  }

  public function ver($idEncrypted)
  {
    try {
      $id = EncryptHelper::decrypt($idEncrypted);
      $rol = SeguridadRol::with(['seguridadRolPermiso'])->find($id);

      if (!$rol) {
        return response()->json(['message' => 'Rol no encontrado',], 404);
      }

      return response()->json($rol, 200);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID o encontrar el rol', 'error' => $e->getMessage(),], 400);
    }
  }

  public function crear()
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_rol,nombre',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $rol = SeguridadRol::create([
      'nombre' => request()->get('nombre'),
      'id_estado' => 1,
    ]);

    return response()->json([
      'message' => 'El rol ha sido creado',
      'payload' => $rol,
    ], 201);
  }

  public function actualizar($idEncrypted)
  {
    $id = EncryptHelper::decrypt($idEncrypted);
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_rol,nombre,' . $id,
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $rol = SeguridadRol::find($id);
      $rol->nombre = request()->get('nombre');
      $rol->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al actualizar el rol',
      ], 500);
    }

    return response()->json([
      'message' => 'El rol ha sido actualizado',
    ], 200);
  }

  public function cambiarEstado($id, $id_estado)
  {
    $validator = Validator::make(['id' => $id, 'id_estado' => $id_estado], [
      'id' => 'required|integer|exists:seguidad_rol,id',
      'id_estado' => 'required|integer|exists:estado,id|in:1,2'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $perfil = SeguridadRol::find($id);

      $perfil->id_estado = $id_estado;
      $perfil->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al cambiar el estado del rol',
      ], 500);
    }

    return response()->json([
      'message' => 'El estado del rol ha sido actualizado',
      'payload' => $perfil,
    ], 200);
  }

  public function agregarPermiso($idEncrypted)
  {
    $validator = Validator::make(request()->all(), [
      'permisos' => ['required', 'array'],
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $id = EncryptHelper::decrypt($idEncrypted);
      $rol = SeguridadRol::find($id);

      if (!$rol) {
        return response()->json(['message' => 'Rol no encontrado',], 404);
      }

      $permisos = array_map([EncryptHelper::class, 'decrypt'], request()->get('permisos'));
      $rol->permisos()->attach($permisos);
      $message = count($permisos) > 1 ? 'Permisos agregados al rol' : 'Permiso agregado al rol';
      return response()->json([
        'message' => $message,
        'payload' => $rol,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al agregar los permisos al rol',
        'line'    => $e->getLine(),
        'error'    => $e->getMessage(),
      ], 500);
    }
  }

  public function permisos($idEncrypted)
  {
    $page    = request()->get('page') ?? 1;
    $perPage = request()->get('per_page') ?? 10;
    $search  = request()->get('search') ?? null;
    try {
      $id = EncryptHelper::decrypt($idEncrypted);
      $rol = SeguridadRol::find($id);

      if (!$rol) {
        return response()->json(['message' => 'Rol no encontrado',], 404);
      }

      $query = $rol->permisos();
      if ($search !== null) {
        $query->whereRaw("LOWER(nombre) LIKE ?", ["%" . strtolower($search) . "%"]);
      }
      $permisos = $query->paginate($perPage, ['*'], 'page', $page);
      $permisos->getCollection()->transform(function ($item) {
        return [
          'id'            => EncryptHelper::encrypt($item->id),
          'nombre'        => $item->nombre,
          'descripcion'   => $item->descripcion,
          'nombre_modulo' => $item->modulo->nombre,
        ];
      });
      return response()->json([
        'data'       => $permisos->items(),
        'total_data' => $permisos->total(),
        'last_page'  => $permisos->lastPage(),
        'page'       => (int) $page,
        'per_page'   => (int) $perPage,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al momento de obtener permisos del rol',
        'line'    => $e->getLine(),
        'error'   => $e->getMessage(),
      ], 500);
    }
  }

  public function eliminarPermiso($idEncrypted)
  {
    $validator = Validator::make(request()->all(), [
      'permiso' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $id = EncryptHelper::decrypt($idEncrypted);
      $rol = SeguridadRol::find($id);

      if (!$rol) {
        return response()->json(['message' => 'Rol no encontrado',], 404);
      }

      $permiso = EncryptHelper::decrypt(request()->get('permiso'));
      $rol->permisos()->detach($permiso);
      return response()->json([
        'message' => 'Permiso eliminado del rol',
        'payload' => $rol,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al eliminar el permiso del rol',
        'line'    => $e->getLine(),
      ], 500);
    }
  }

  public function eliminarRol($idEncrypted)
  {
    try {
      $id = EncryptHelper::decrypt($idEncrypted);
      $rol = SeguridadRol::with('proyectoParticipante')->find($id);

      if (!$rol) {
        return response()->json(['message' => 'Rol no encontrado'], 404);
      }

      if ($rol->proyectoParticipante->isNotEmpty() && $rol->proyectoParticipante->every('id_estado', '!=', 2)) {
        return response()->json(['message' => 'No se puede eliminar el rol porque está asignado a un usuario'], 400);
      }

      $rol->delete();

      return response()->json(['message' => 'El rol ha sido eliminado'], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al eliminar el rol',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
