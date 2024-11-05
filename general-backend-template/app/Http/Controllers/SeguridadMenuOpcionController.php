<?php

namespace App\Http\Controllers;

use App\Helpers\EncryptHelper;
use App\Models\Seguridad\SeguridadModulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Seguridad\SeguridadMenuOpcion;

class SeguridadMenuOpcionController extends Controller
{
  const ACTIVO = 1;

  public function listar(Request $request)
  {
    $idModulo = $request->query('id_modulo') ?? null;
    $busqueda = $request->query('busqueda') ?? null;

    $menuOpciones = SeguridadMenuOpcion::where('id_estado', 1)->with(['modulo', 'parent']);

    if ($idModulo) {
      if (!$modulo = SeguridadModulo::find($idModulo)) {
        return response()->json([
          'message' => 'Parametros invalidos',
        ], 400);
      }
      $menuOpciones->where('id_modulo', $modulo->id);
    }

    if (!empty($busqueda)) {
      $menuOpciones->where(function ($query) use ($busqueda) {
        $query->whereRaw('LOWER(nombre) like ?', ['%' . strtolower($busqueda) . '%'])->orWhereRaw('LOWER(uri) like ?', ['%' . strtolower($busqueda) . '%']);
      });
    }

    $menu = $menuOpciones->get();
    $data = $menu->map(function ($item) {
      $encryptId = EncryptHelper::encrypt($item->id);
      return [
        'id'      => $encryptId,
        'nombre'  => $item->nombre,
        'uri'     => $item->uri,
        'parent'  => $item->parent ? $item->parent->nombre : 'N/A',
        'mostrar' => $item->mostrar_menu ? 'Si' : 'No',
        'modulo'  => $item->modulo ? $item->modulo->nombre : 'N/A',
        'id_estado' => $item->id_estado,
      ];
    });
    return response()->json($data, 200);
  }

  public function ver($idEncrypted)
  {
    $id = EncryptHelper::decrypt($idEncrypted);
    $menuOpcion = SeguridadMenuOpcion::with(['modulo', 'parent', 'permisos'])->find($id);

    if (!$menuOpcion) {
      return response()->json(['message' => 'Menu no encontrado',], 404);
    }

    $data = collect([$menuOpcion])->map(function ($item) {
      $encryptId = EncryptHelper::encrypt($item->id);
      return [
        'id'       => $encryptId,
        'nombre'   => $item->nombre,
        'nombre_unico' => $item->nombre_unico,
        'icono'    => $item->icono,
        'uri'      => $item->uri,
        'parent' => $item->parent ? [
          'id' => EncryptHelper::encrypt($item->parent->id),
          'nombre' => $item->parent->nombre,
        ] : null,
        'mostrar'  => $item->mostrar_menu,
        'requiere_autenticacion' => $item->requiere_autenticacion,
        'enrutable' => $item->enrutable,
        'dependencia_menu_padre' => $item->dependencia_menu_padre,
        'componente' => $item->componente,
        'modulo'   => collect([$item->modulo])->map(function ($modulo) {
          return [
            'id' => EncryptHelper::encrypt($modulo->id),
            'nombre' => $modulo->nombre,
            'acronimo' => $modulo->acronimo,
            'id_estado' => $modulo->id_estado,
          ];
        })->first(),
        'permisos' => $item->permisos,
      ];
    });
    return response()->json($data, 200);
  }

  public function crear()
  {
    $validator = Validator::make(request()->all(), [
      'nombre'                => 'required|string|max:100',
      'nombre_unico'          => 'required|string|max:100|regex:/^[a-zA-Z0-9_-]*$/',
      'icono'                 => 'nullable|string|max:255',
      'uri'                   => 'required_if:enrutable,true|nullable|string|max:255',
      'componente'            => 'required_if:enrutable,true|nullable|string|max:255',
      'id_menu_opcion_padre'  => 'required_if:dependencia_menu_padre,true|nullable|string',
      'id_modulo'             => 'required|string',
    ], [
      'nombre.required'          => 'El campo "nombre" es obligatorio.',
      'nombre.string'            => 'El campo "nombre" debe ser una cadena de texto.',
      'nombre.max'               => 'El campo "nombre" no debe exceder los 100 caracteres.',

      'nombre_unico.required'    => 'El campo "nombre único" es obligatorio.',
      'nombre_unico.string'      => 'El campo "nombre único" debe ser una cadena de texto.',
      'nombre_unico.max'         => 'El campo "nombre único" no debe exceder los 100 caracteres.',
      'nombre_unico.regex'       => 'El campo "nombre único" solo puede contener letras minúsculas sin espacios.',

      'icono.string'             => 'El campo "icono" debe ser una cadena de texto.',
      'icono.max'                => 'El campo "icono" no debe exceder los 255 caracteres.',

      'uri.required_if'          => 'El campo "Path" es obligatorio cuando "Contiene una ruta" es verdadero.',
      'uri.string'               => 'El campo "Path" debe ser una cadena de texto.',
      'uri.max'                  => 'El campo "Path" no debe exceder los 255 caracteres.',

      'componente.required_if'   => 'El campo "componente" es obligatorio cuando "Contiene una ruta" es verdadero.',
      'componente.string'        => 'El campo "componente" debe ser una cadena de texto.',
      'componente.max'           => 'El campo "componente" no debe exceder los 255 caracteres.',

      'id_menu_opcion_padre.required_if'  => 'El campo "opción de menú padre" es obligatorio cuando "dependencia de otra ruta" es verdadero.',
      'id_menu_opcion_padre.string'      => 'El campo "opción de menú padre" debe ser un número entero.',

      'id_modulo.required'       => 'El campo "módulo" es obligatorio.',
      'id_modulo.string'        => 'El campo "módulo" debe ser un id encriptado.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $id_modulo = EncryptHelper::decrypt(request()->get('id_modulo'));
      if (empty($id_modulo) || !SeguridadModulo::find($id_modulo)) {
        return response()->json([
          'message' => 'El módulo seleccionado no existe',
        ], 400);
      }

      if (request()->get('dependencia_menu_padre')) {
        $id_menu_opcion_padre = EncryptHelper::decrypt(request()->get('id_menu_opcion_padre'));
        if (empty($id_menu_opcion_padre) || !SeguridadMenuOpcion::find($id_menu_opcion_padre)) {
          return response()->json([
            'message' => 'La opción de menú padre seleccionada no existe',
          ], 400);
        }
      }

      $data = SeguridadMenuOpcion::create([
        'nombre'                => request()->get('nombre'),
        'nombre_unico'          => request()->get('nombre_unico'),
        'icono'                 => request()->get('icono'),
        'uri'                   => request()->get('uri'),
        'componente'            => request()->get('componente'),
        'id_menu_opcion_padre'  => $id_menu_opcion_padre ?? null,
        'id_modulo'             => $id_modulo,
        'mostrar_menu'          => (bool)request()->get('mostrar_menu'),
        'requiere_autenticacion' => (bool)request()->get('requiere_autenticacion'),
        'enrutable'             => (bool)request()->get('enrutable'),
        'dependencia_menu_padre' => (bool)request()->get('dependencia_menu_padre'),
        'id_estado'             => self::ACTIVO,
      ]);

      $data->id = EncryptHelper::encrypt($data->id);

      return response()->json([
        'message' => 'Registro creado existosamente',
        'payload' => $data,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Ha ocurrido un error al crear el registro' . $e->getMessage(),
        'line'    => $e->getLine(),
      ], 500);
    }
  }

  public function actualizar($id)
  {
    $idRuta = EncryptHelper::decrypt($id);
    $idModulo = EncryptHelper::decrypt(request()->get('id_modulo'));
    request()->merge(['id_modulo_decrypt' => $idModulo]);

    if (request()->get('id_menu_opcion_padre')) {
      $idMenuOpcionPadre = EncryptHelper::decrypt(request()->get('id_menu_opcion_padre'));
      request()->merge(['id_menu_opcion_padre_decrypt' => $idMenuOpcionPadre]);
    }

    $validator = Validator::make(request()->all(), [
      'nombre' => 'required|string|max:100',
      'icono' => 'required|string|max:255',
      'uri' => 'nullable|string|max:255',
      'id_menu_opcion_padre_decrypt' => 'nullable|integer|exists:seguridad_menu_opcion,id',
      'id_modulo_decrypt' => 'required|integer|exists:seguridad_modulo,id',
      'id_estado' => 'integer|exists:estado,id|in:1,2',
      'mostrar' => 'boolean',
      'requiere_autenticacion' => 'boolean',
      'enrutable' => 'boolean',
      'dependencia_menu_padre' => 'boolean',
      'componente' => 'required|string|max:255',
      'nombre_unico' => 'required|string|max:100|regex:/^[a-zA-Z0-9_-]*$/',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    if (!$menuOpcion = SeguridadMenuOpcion::find($idRuta)) {
      return response()->json([
        'message' => 'Parametros invalidos',
      ], 400);
    }

    try {
      $menuOpcion->nombre = request()->get('nombre');
      $menuOpcion->icono = request()->get('icono');
      $menuOpcion->uri = request()->get('uri');
      $menuOpcion->enrutable = request()->get('enrutable') ? true : false;
      $menuOpcion->componente = request()->get('componente');
      $menuOpcion->mostrar_menu = request()->get('mostrar') ? true : false;
      $menuOpcion->requiere_autenticacion = request()->get('requiere_autenticacion') ? true : false;
      $menuOpcion->dependencia_menu_padre = request()->get('dependencia_menu_padre') ? true : false;
      $menuOpcion->nombre_unico = request()->get('nombre_unico');
      $menuOpcion->id_modulo = request()->get('id_modulo_decrypt');

      if (request()->get('id_estado')) {
        $menuOpcion->id_estado = request()->get('id_estado');
      }

      !request('dependencia_menu_padre')
        ? $menuOpcion->id_menu_opcion_padre = null
        : $menuOpcion->id_menu_opcion_padre = request()->get('id_menu_opcion_padre_decrypt');

      $menuOpcion->save();
    } catch (\Exception $e) {
      \Log::error($e);
      return response()->json([
        'message' => 'Ha ocurrido un error al actualizar el registro',
      ], 500);
    }

    return response()->json([
      'message' => 'Registro actualizado con exito',
      'payload' => $menuOpcion,
    ], 200);
  }

  public function cambiarEstado($id)
  {

    $idRuta = EncryptHelper::decrypt($id);

    $validator = Validator::make(['id' => $idRuta], [
      'id' => 'required|integer|exists:seguridad_menu_opcion,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    try {
      $menuOpcion = SeguridadMenuOpcion::find($idRuta);
      $menuOpcion->id_estado = $menuOpcion->id_estado == 1 ? 2 : 1;
      $menuOpcion->save();
    } catch (\Exception $e) {
      \Log::error($e);
      return response()->json([
        'message' => 'Ha ocurrido un error al cambiar el estado del perfil',
      ], 500);
    }

    return response()->json([
      'message' => 'Estado actualizado con exito',
      'payload' => $menuOpcion,
    ], 200);
  }

  public function cambiarMostrarMenu($id, Request $request)
  {
    $idRuta = EncryptHelper::decrypt($id);

    $validator = Validator::make(['id' => $idRuta, 'mostrar' => $request->mostrar], [
      'id' => 'required|integer|exists:seguridad_menu_opcion,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    if (filter_var($request->mostrar ?? true, FILTER_VALIDATE_BOOLEAN)) {
      $request->merge(['mostrar_menu' => true]);
    } else {
      $request->merge(['mostrar_menu' => false]);
    }

    try {
      $menuOpcion = SeguridadMenuOpcion::find($idRuta);
      $menuOpcion->mostrar_menu = $request->mostrar_menu;
      $menuOpcion->save();
    } catch (\Exception $e) {
      \Log::error($e);
      return response()->json([
        'message' => 'Ha ocurrido un error al cambiar el estado del perfil',
      ], 500);
    }
  }
}
