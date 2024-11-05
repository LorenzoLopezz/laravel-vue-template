<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;
use App\Models\Seguridad\SeguridadPerfil;
use Illuminate\Support\Facades\Validator;

class SeguridadPerfilController extends Controller
{
  public function listar()
  {
    $perfiles = SeguridadPerfil::get();
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
      $perfil = SeguridadPerfil::with(['permisos'])->find($id);

      if (!$perfil) {
        return response()->json(['message' => 'Perfil no encontrado',], 404);
      }

      return response()->json($perfil, 200);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID o encontrar el perfil', 'error' => $e->getMessage(),], 400);
    }
  }

  public function crear()
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_perfil,nombre',
      'acronimo' => 'required|string|max:10|unique:seguridad_perfil,acronimo',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $perfil = SeguridadPerfil::create([
      'nombre' => request()->get('nombre'),
      'acronimo' => request()->get('acronimo'),
      'id_estado' => 1,
    ]);

    return response()->json([
      'message' => 'El perfil ha sido creado',
      'payload' => $perfil,
    ], 201);
  }

  public function actualizar($id)
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_perfil,nombre,' . $id,
      'acronimo' => 'required|string|max:10|unique:seguridad_perfil,acronimo,' . $id,
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $perfil = SeguridadPerfil::find($id);
      $perfil->nombre = request()->get('nombre');
      $perfil->acronimo = request()->get('acronimo');
      $perfil->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al actualizar el perfil',
      ], 500);
    }

    return response()->json([
      'message' => 'El perfil ha sido actualizado',
      'payload' => $perfil,
    ], 200);
  }

  public function cambiarEstado($id, $id_estado)
  {
    $validator = Validator::make(['id' => $id, 'id_estado' => $id_estado], [
      'id' => 'required|integer|exists:seguridad_perfil,id',
      'id_estado' => 'required|integer|exists:estado,id|in:1,2'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $perfil = SeguridadPerfil::find($id);

      $perfil->id_estado = $id_estado;
      $perfil->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al cambiar el estado del perfil',
      ], 500);
    }

    return response()->json([
      'message' => 'El estado del perfil ha sido actualizado',
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
      $perfil = SeguridadPerfil::find($id);

      if (!$perfil) {
        return response()->json(['message' => 'Perfil no encontrado',], 404);
      }

      $permisos = array_map([EncryptHelper::class, 'decrypt'], request()->get('permisos'));
      $perfil->permisos()->attach($permisos);
      $message = count($permisos) > 1 ? 'Permisos agregados al perfil' : 'Permiso agregado al perfil';
      return response()->json([
        'message' => $message,
        'payload' => $perfil,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al agregar los permisos al perfil',
        'line'    => $e->getLine(),
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
      $perfil = SeguridadPerfil::find($id);

      if (!$perfil) {
        return response()->json(['message' => 'Perfil no encontrado',], 404);
      }

      $query = $perfil->permisos();
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
        'message' => 'Ha ocurrido un error al momento de obtener permisos del perfil',
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
      $perfil = SeguridadPerfil::find($id);

      if (!$perfil) {
        return response()->json(['message' => 'Perfil no encontrado',], 404);
      }

      $permiso = EncryptHelper::decrypt(request()->get('permiso'));
      $perfil->permisos()->detach($permiso);
      return response()->json([
        'message' => 'Permiso eliminado del perfil',
        'payload' => $perfil,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al eliminar el permiso del perfil',
        'line'    => $e->getLine(),
      ], 500);
    }
  }
}
