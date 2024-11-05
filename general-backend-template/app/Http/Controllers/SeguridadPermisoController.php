<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;
use App\Models\Seguridad\SeguridadPermiso;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SeguridadPermisoController extends Controller
{
  public function listar(Request $request)
  {
    $page = request()->get('page') ?? 1;
    $perPage = request()->get('per_page') ?? 10;
    $search = request()->get('search') ?? null;
    $nombre = $request->query('nombre') ?? null;
    $idmodulo = $request->query('id_modulo') ?? null;
    $paginate = $request->query('paginate') ?? true;

    if (!$nombre && !$idmodulo) {
      $permisos = SeguridadPermiso::with(['modulo'])
        ->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($search) . '%']);
      if ($paginate) {
        $permisos = $permisos->paginate($perPage, ['*'], 'page', $page);
      }
    } else {
      $permisos = SeguridadPermiso::whereNotNull('id');

      if ($nombre) {
        $permisos->where('nombre', 'LIKE', "%{$nombre}%");
      }
      if ($search) {
        $permisos->where('nombre', 'LIKE', "%{$search}%");
      }
      if ($idmodulo) {
        $permisos->where('id_modulo', '=', $idmodulo);
      }

      $permisos = $permisos->get();
    }

    $data = $permisos->map(function ($item) {
      $modulo = $item->modulo()->select('nombre')->first();
      $encryptId = EncryptHelper::encrypt($item->id);
      return [
        'id'               => $encryptId,
        'descripcion'      => $item->descripcion,
        'nombre'           => $item->nombre,
        'modulo'           => $modulo->nombre,
      ];
    });
    return response()->json(
      [
        'total_data' => $permisos->total(),
        'last_page' => $permisos->lastPage(),
        'data' => $data,
        'page' => $page,
        'per_page' => $perPage,
      ],
      200
    );
  }

  public function ver($encryptedId)
  {
    try {
      $id = EncryptHelper::decrypt($encryptedId);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID'], 400);
    }

    $permiso = SeguridadPermiso::find($id);

    if (!$permiso) {
      return response()->json(['message' => 'Permiso no encontrado',], 404);
    }

    return response()->json($permiso, 200);
  }

  public function crear()
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:200|unique:seguridad_permiso,nombre',
      'id_modulo' => 'required|integer|exists:seguridad_modulo,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $permiso = SeguridadPermiso::create([
      'id_modulo' => request()->get('id_modulo'),
      'nombre' => request()->get('nombre'),
      'descripcion' => request()->get('descripcion') ?? ''
    ]);

    return response()->json([
      'message' => 'Registro creado con exito!',
      'payload' => $permiso,
    ], 201);
  }

  public function actualizar($id)
  {
    $validator = Validator::make(request()->all(), [
      'descripcion' => 'required|string',
      'id_modulo' => 'required|integer|exists:seguridad_modulo,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $permiso = SeguridadPermiso::find($id);
      $permiso->descripcion = request()->get('descripcion');
      $permiso->id_modulo = request()->get('id_modulo');
      $permiso->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al actualizar el permiso',
      ], 500);
    }

    return response()->json([
      'message' => 'El registro ha sido actualizado',
      'payload' => $permiso,
    ], 200);
  }

  public function eliminar($encryptedId)
  {
    // Desencriptar el ID utilizando EncryptHelper
    try {
      $id = EncryptHelper::decrypt($encryptedId);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID'], 400);
    }

    $validator = Validator::make(['id' => $id], [
      'id' => 'required|integer|exists:seguridad_permiso,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $permiso = SeguridadPermiso::find($id);
    $permiso->delete();

    return response()->json([
      'message' => 'El permiso ha sido eliminado',
    ], 200);
  }
}
