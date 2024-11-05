<?php

namespace App\Http\Controllers;

use App\Notifications\VerificarEmailNotificacion;
use Illuminate\Http\Request;
use App\Models\Auth\AuthUsuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\AuthUsuarioPerfil;
use App\Models\Auth\AuthUsuarioPermiso;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Models\Seguridad\SeguridadMenuOpcion;
use App\Models\Seguridad\SeguridadPermiso;
use Illuminate\Support\Arr;
use App\Helpers\EncryptHelper;
use Illuminate\Support\Str;

class AuthUsuarioController extends Controller
{
  const USUARIO_ACTIVO = 1;
  const USUARIO_BLOQUEADO = 14;

  public function listar()
  {
    $page = request()->get('page') ?? 1;
    $perPage = request()->get('per_page') ?? 10;
    $isPaginate = request()->get('paginate') ?? 'true';
    $search = request()->get('search') ?? null;

    if ($isPaginate === 'true') {
      $usuarios = AuthUsuario::addSelect(
        'auth_usuario.*',
        DB::raw("concat_ws(' ', primer_nombre, segundo_nombre, primer_apellido, segundo_apellido) as nombre_completo")
      )
        ->with(['permisos', 'perfiles', 'institucionArea']);

      if ($search !== null) {
        $usuarios->whereRaw("LOWER(concat_ws(' ', primer_nombre, segundo_nombre, primer_apellido, segundo_apellido)) LIKE ?", ["%" . strtolower($search) . "%"]);
      }

      $usuarios = $usuarios->paginate($perPage, ['*'], 'page', $page);
    } else {
      $usuarios = AuthUsuario::with(['permisos', 'perfiles', 'institucionArea'])->get();
    }

    $data = $usuarios->map(function ($item) {
      $creadoPor = $item->creador()->select('email')->first();
      $estado = $item->estado()->select('nombre')->first();
      $encryptId = EncryptHelper::encrypt($item->id);
      $perfiles = $item->perfiles->map(function ($perfil) {
        return [
          'id' => $perfil->id_encriptado,
          'nombre' => $perfil->nombre,
        ];
      });
      return [
        'id'               => $encryptId,
        'codigo'           => $item->codigo,
        'username'         => $item->username,
        'email'            => $item->email,
        'verificado'       => $item->verificado,
        'id_estado'        => $item->id_estado,
        'creado_por'       => $item->creado_por,
        'ultima_sesion'    => $item->ultima_sesion,
        'created_at'       => $item->created_at,
        'creado_por_email' => $creadoPor["email"] ?? '',
        'estado_nombre'    => $estado["nombre"],
        'perfiles'         => $perfiles,
        'institucion_area' => $item->institucionArea,
        'nombre'           => $item->nombre_completo,
      ];
    });

    return response()->json(
      [
        'total_data' => $usuarios->total(),
        'last_page' => $usuarios->lastPage(),
        'data' => $data,
        'page' => $page,
        'per_page' => $perPage,
      ],
      200
    );
  }

  public function crear()
  {
    try {
      $data = request()->only([
        'email',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'id_institucion_area',
        'perfiles'
      ]);

      $validator = Validator::make($data, [
        'email'              => 'required|email|unique:auth_usuario',
        'primer_nombre'      => 'required|string',
        'primer_apellido'    => 'required|string',
        'id_institucion_area' => 'required|integer',
        'perfiles'           => 'required|array',
        'fecha_nacimiento'   => 'nullable|date|date_format:Y-m-d',
      ], [
        'email.required'               => 'El correo electrónico es obligatorio.',
        'email.email'                  => 'Debe ser un correo electrónico válido.',
        'email.unique'                 => 'Este correo ya está registrado.',
        'primer_nombre.required'       => 'El primer nombre es obligatorio.',
        'primer_apellido.required'     => 'El primer apellido es obligatorio.',
        'id_institucion_area.required' => 'El ID de área es obligatoria.',
        'id_institucion_area.integer'  => 'El ID de área debe ser un número entero.',
        'perfiles.required'            => 'Debe asignar al menos un perfil.',
        'perfiles.array'               => 'El formato de perfiles no es válido.',
        'fecha_nacimiento.date'        => 'La fecha de nacimiento debe ser una fecha válida.',
        'fecha_nacimiento.date_format' => 'El formato de la fecha de nacimiento debe ser YYYY-MM-DD.',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'message' => $validator->errors()->first(),
        ], 422);
      }

      $username = strtolower(substr($data['primer_nombre'], 0, 1) . substr($data['primer_apellido'], 0, 1)) . Str::random(6);
      $token = Str::random(60);
      $password = Str::random(8);

      DB::beginTransaction();

      $user = AuthUsuario::create([
        'username'           => $username,
        'codigo'             => Str::uuid()->toString(),
        'email'              => $data['email'],
        'password'           => Hash::make($password),
        'verificado'         => false,
        'id_estado'          => self::USUARIO_ACTIVO,
        'primer_nombre'      => $data['primer_nombre'],
        'segundo_nombre'     => $data['segundo_nombre'] ?? null,
        'primer_apellido'    => $data['primer_apellido'],
        'segundo_apellido'   => $data['segundo_apellido'] ?? null,
        'fecha_nacimiento'   => $data['fecha_nacimiento'] ?? null,
        'id_institucion_area' => $data['id_institucion_area'],
        'remember_token'     => $token,
        'creado_por'         => auth()->user()->id,
      ]);

      foreach ($data['perfiles'] as $perfilId) {
        $id = EncryptHelper::decrypt($perfilId);
        AuthUsuarioPerfil::create([
          'id_usuario' => $user->id,
          'id_perfil'  => $id,
          'creado_por' => auth()->user()->id,
        ]);
      }

      Notification::send($user, new VerificarEmailNotificacion($token, $password));

      $encryptId = EncryptHelper::encrypt($user->id);
      $user->id  = $encryptId;

      DB::commit();

      return response()->json([
        'message' => 'Usuario creado exitosamente',
        'data' => [
          'id'         => $user->id,
          'codigo'     => $user->codigo,
          'username'   => $user->username,
          'email'      => $user->email,
          'verificado' => $user->verificado,
          'id_estado'  => $user->id_estado,
        ]
      ], 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'message' => 'Error al registrar el usuario',
        'error' => $e->getMessage(),
        'linea' => $e->getLine(),
      ], 500);
    }
  }

  public function editar($encryptedId)
  {
    try {
      $id = EncryptHelper::decrypt($encryptedId);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID', 'error' => $e->getMessage()], 400);
    }

    if (!$usuario = AuthUsuario::find($id)) {
      return response()->json([
        'message' => 'Registro no encontrado',
      ], 404);
    }

    $validator = Validator::make(request()->all(), [
      'primer_nombre'      => 'required|string',
      'primer_apellido'    => 'required|string',
      'id_institucion_area' => 'required|integer',
      'perfiles'           => 'required|array',
      'fecha_nacimiento'   => 'nullable|date|date_format:Y-m-d',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    DB::beginTransaction();
    try {
      $usuario->email = request()->get('email') ?? null;
      $usuario->primer_nombre = request()->get('primer_nombre') ?? null;
      $usuario->segundo_nombre = request()->get('segundo_nombre') ?? null;
      $usuario->primer_apellido = request()->get('primer_apellido') ?? null;
      $usuario->segundo_apellido = request()->get('segundo_apellido') ?? null;
      $usuario->fecha_nacimiento = request()->get('fecha_nacimiento') ?? null;
      $usuario->id_institucion_area = request()->get('id_institucion_area') ?? null;
      $usuario->save();

      $perfiles = array_map(function ($item) use ($usuario) {
        return [
          "id_usuario" => $usuario->id,
          "id_perfil" => EncryptHelper::decrypt($item),
          "creado_por" => auth()->user()->id
        ];
      }, request()->get('perfiles'));

      AuthUsuarioPerfil::where('id_usuario', $usuario->id)
        ->whereNotIn('id_perfil', Arr::pluck($perfiles, 'id_perfil'))
        ->delete();
      AuthUsuarioPerfil::upsert($perfiles, ['id_usuario', 'id_perfil'], []);

      if (request()->get('permisos')) {
        $permisosInsertar = array_map(
          function ($item) use ($usuario) {
            return [
              "id_usuario" => $usuario->id,
              "id_permiso" => $item->id,
              "creado_por" => auth()->user()->id
            ];
          },
          request()->get('permisos')
        );
        AuthUsuarioPermiso::where('id_usuario', $usuario->id)
          ->whereNotIn('id_permiso', Arr::pluck($permisosInsertar, 'id_permiso'))
          ->delete();
        AuthUsuarioPermiso::upsert($permisosInsertar, ['id_usuario', 'id_permiso'], []);
      }

      DB::commit();
    } catch (\Exception $ex) {
      DB::rollBack();
      \Log::error($ex);
      return response()->json([
        'message' => $ex->getMessage(),
      ], 500);
    }

    return response()->json([
      'message' => 'Usuario creado exitosamente',
      'data' => $usuario,
    ], 200);
  }

  public function ver($encryptedId)
  {
    try {
      $id = EncryptHelper::decrypt($encryptedId);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID'], 400);
    }

    $usuario = AuthUsuario::with(['permisos', 'perfiles'])
      ->leftJoin('institucion_area as ia', 'ia.id', '=', 'auth_usuario.id_institucion_area')
      ->leftJoin('institucion_establecimiento as ie', 'ie.id', '=', 'ia.id_establecimiento')
      ->addSelect('auth_usuario.*', 'ia.id as id_institucion_area', 'ie.id as id_institucion_establecimiento', 'ie.id_institucion')
      ->where('auth_usuario.id', $id)
      ->first();

    if (!$usuario) {
      return response()->json(['message' => 'Usuario no encontrado',], 404);
    }

    return response()->json($usuario, 200);
  }

  public function informacion()
  {
    try {
      $usuario = AuthUsuario::with(['perfiles'])->find(auth()->user()->id);
      if (!$usuario) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
      }

      return response()->json($usuario, 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Error al obtener la información del usuario',
        'error'   => $e->getLine(),
      ], 500);
    }
  }

  public function menu()
  {
    $usuario = AuthUsuario::with(['perfiles', 'permisos'])->find(auth()->id());

    if (!$usuario) {
      return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $perfiles = $usuario->perfiles()->pluck('perfiles.id');
    $permisos = $usuario->permisos()->pluck('seguridad_permiso.id');

    $menuOpciones = SeguridadMenuOpcion::with(['permisos'])
      ->select('seguridad_menu_opcion.*')
      ->join('seguridad_menu_opcion_permiso as smop', 'seguridad_menu_opcion.id', '=', 'smop.id_menu_opcion')
      ->leftJoin('seguridad_perfil_permiso as spp', 'smop.id_permiso', '=', 'spp.id_permiso')
      ->whereIn('spp.id_perfil', $perfiles)
      ->orWhereIn('smop.id_permiso', $permisos)
      ->orderBy('seguridad_menu_opcion.id', 'asc')
      ->orderBy('seguridad_menu_opcion.id_menu_opcion_padre', 'asc')
      ->get()
      ->map(function ($item) {
        return [
          'id' => $item->id,
          'nombre' => $item->nombre,
          'nombre_unico' => $item->nombre_unico,
          'icono' => $item->icono,
          'uri' => $item->uri,
          'componente' => $item->componente,
          'id_menu_opcion_padre' => $item->id_menu_opcion_padre,
          'id_modulo' => $item->id_modulo,
          'mostrar_menu' => $item->mostrar_menu,
          'requiere_autenticacion' => $item->requiere_autenticacion,
          'enrutable' => $item->enrutable,
          'dependencia_menu_padre' => $item->dependencia_menu_padre,
          'id_estado' => $item->id_estado,
          'enrutable' => $item->enrutable,
          'permisos' => $item->permisos->map(function ($permiso) {
            return [
              'id' => $permiso->id,
              'nombre' => $permiso->nombre,
            ];
          })->toArray(),
        ];
      })->toArray();

    $menu = collect($menuOpciones)->whereNull('id_menu_opcion_padre')->values();
    $menuPadres = SeguridadMenuOpcion::whereNull('id_menu_opcion_padre')
      ->where('enrutable', false)
      ->orderBy('id', 'asc')
      ->get();

    foreach ($menuPadres as $value) {
      $items = $this->buscarItemsMenuHijos($value, collect($menuOpciones));
      if ($items->isNotEmpty()) {
        $value->children = $items;
        $menu->push($value);
      }
    }

    return response()->json($menu->toArray(), 200);
  }

  public function editarPassword($encryptedId)
  {
    try {
      $id = EncryptHelper::decrypt($encryptedId);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Error al desencriptar el ID', 'error' => $e->getMessage(),], 400);
    }

    $usuario = AuthUsuario::find($id);

    if (!$usuario) {
      return response()->json(['message' => 'Registro no encontrado',], 404);
    }

    $validator = Validator::make(request()->all(), [
      'password' => 'required|string|min:6|max:20',
      'password_repeat' => 'required|same:password',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 400);
    }

    $usuario->password = Hash::make(request()->get('password'));
    $usuario->save();

    return response()->json(["data" => $usuario, "message" => "Registro actualizado"], 200);
  }

  public function permisos()
  {
    $usuario = AuthUsuario::find(auth()->user()->id);

    if (!$usuario) {
      return response()->json(['message' => 'Usuario no encontrado',], 404);
    }

    $permisosPerfil = SeguridadPermiso::select('seguridad_permiso.nombre', 'seguridad_permiso.descripcion')
      ->join('seguridad_perfil_permiso as spp', 'seguridad_permiso.id', '=', 'spp.id_permiso')
      ->join('auth_usuario_perfil as aup', 'spp.id_perfil', '=', 'aup.id_perfil')
      ->where('aup.id_usuario', $usuario->id);

    $permisosUsuario = SeguridadPermiso::select('seguridad_permiso.nombre', 'seguridad_permiso.descripcion')->join('auth_usuario_permiso as aup', 'seguridad_permiso.id', '=', 'aup.id_permiso')->where('aup.id_usuario', $usuario->id);

    $permisos = $permisosPerfil->union($permisosUsuario)->get();

    return response()->json($permisos, 200);
  }

  private function buscarItemsMenuHijos($itemPadre, $opcionesUsuario)
  {
    $items = collect([]);
    $itemsHijosOpciones = $opcionesUsuario
      ->where('id_menu_opcion_padre', $itemPadre->id)
      ->where('enrutable', true)
      ->where('mostrar_menu', true)
      ->values();

    $itemsSubmenus = SeguridadMenuOpcion::where('id_menu_opcion_padre', $itemPadre->id)
      ->where('enrutable', false)->get();

    if (count($itemsHijosOpciones) > 0) {
      foreach ($itemsHijosOpciones as $item) {
        $items->push($item);
      }
    }

    if (count($itemsSubmenus) > 0) {
      foreach ($itemsSubmenus as $item) {
        $item->children = collect([]);
        $itemsEncontrados = $this->buscarItemsMenuHijos($item, $opcionesUsuario);
        if (count($items) > 0) {
          $item->children = $itemsEncontrados;
          $items->push($item);
        }
      }
    }

    return $items;
  }

  public function bloquearUsuario(Request $request, $encryptedId)
  {

    $request->validate([
      'justificacion' => 'required|string'
    ]);
    try {
      $idUsuario = EncryptHelper::decrypt($encryptedId);

      $usuario = AuthUsuario::find($idUsuario);
      if (!$usuario) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
      }

      $usuario->id_estado = self::USUARIO_BLOQUEADO;
      $usuario->justificacion_bloqueo = $request->justificacion;
      $usuario->save();

      $usuario->sesiones()
        ->where('id_estado', 1)
        ->update([
          'id_estado' => 2,
          'closed_at' => date(DATE_FORMAT),
        ]);

      return response()->json(['message' => 'Usuario bloqueado y sesiones eliminadas'], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Error al bloquear el usuario',
        'error'   => $e->getLine(),
      ], 500);
    }
  }
}
