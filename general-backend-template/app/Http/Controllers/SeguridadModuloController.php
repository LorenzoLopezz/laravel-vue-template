<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;
use App\Models\Seguridad\SeguridadModulo;
use Illuminate\Support\Facades\Validator;

class SeguridadModuloController extends Controller
{
  public function listar()
  {
    $modulos = SeguridadModulo::all();
    $data = $modulos->map(function ($item) {
      $encryptId = EncryptHelper::encrypt($item->id);
      return [
        'id'        => $encryptId,
        'nombre'    => $item->nombre,
        'acronimo'  => $item->acronimo,
        'id_estado' => $item->id_estado,
      ];
    });
    return response()->json($data, 200);
  }

  public function ver($id)
  {
    $modulo = SeguridadModulo::find($id);

    if (!$modulo) {
      return response()->json(['message' => 'Módulo no encontrado',], 404);
    }

    return response()->json($modulo, 200);
  }

  public function crear()
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_modulo,nombre',
      'acronimo' => 'required|string|max:10|unique:seguridad_modulo,acronimo',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $modulo = SeguridadModulo::create([
      'nombre' => request()->get('nombre'),
      'acronimo' => request()->get('acronimo'),
      'id_estado' => 1,
    ]);

    return response()->json([
      'message' => 'El módulo ha sido creado',
      'payload' => $modulo,
    ], 201);
  }

  public function actualizar($id)
  {
    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100|unique:seguridad_modulo,nombre,' . $id,
      'acronimo' => 'required|string|max:10|unique:seguridad_modulo,acronimo,' . $id,
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }

    try {
      $modulo = SeguridadModulo::find($id);
      $modulo->nombre = request()->get('nombre');
      $modulo->acronimo = request()->get('acronimo');
      $modulo->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage(),
      ], 500);
    }

    return response()->json([
      'message' => 'El módulo ha sido actualizado',
      'payload' => $modulo,
    ], 200);
  }

  public function cambiarEstado($id, $id_estado)
  {
    if ($id_estado != 1 && $id_estado != 2) {
      return response()->json([
        'message' => 'El estado debe ser 1 (Activo) o 2 (Inactivo)',
      ], 422);
    }

    try {
      $modulo = SeguridadModulo::find($id);
      $modulo->id_estado = $id_estado;
      $modulo->save();
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage(),
      ], 500);
    }

    return response()->json([
      'message' => 'El estado del módulo ha sido actualizado',
      'payload' => $modulo,
    ], 200);
  }
}
