<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use JWTAuth;

use App\Models\Auth\AuthUsuario;
use App\Models\Auth\AuthUsuarioSesion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['iniciarSesion', 'refreshToken', 'verificarEmail']]);
  }

  public function iniciarSesion()
  {
    $validator = Validator::make(request()->all(), [
      'email' => 'required|email|exists:auth_usuario,email',
      'password' => 'required|string|min:6|max:20',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }

    $customClaims = [];

    $token = auth()->claims($customClaims)->attempt([
      'email' => request()->get('email'),
      'password' => request()->get('password')
    ]);

    if (!$token) {
      return response()->json([
        'message' => 'Credenciales inválidas',
      ], 401);
    }

    auth()->user()->sesiones()
      ->where('id_estado', 1)
      ->where('closed_at', null)
      ->update([
        'id_estado' => 2,
        'closed_at' => date(DATE_FORMAT),
      ]);

    $user = auth()->user();
    $user->ultima_sesion = date(DATE_FORMAT);
    $user->save();

    $refreshToken = $this->generarRefreshToken($user);

    return $this->respondWithToken($token, $refreshToken);
  }

  public function cuenta()
  {
    return response()->json(auth()->user());
  }

  public function cerrarSesion()
  {
    $usuario = auth()->user();

    if (!$usuario) {
      return response()->json([
        'message' => 'Usuario no encontrado',
      ], 404);
    }

    $usuario->sesiones()
      ->where('id_estado', 1)
      ->update([
        'id_estado' => 2,
        'closed_at' => date(DATE_FORMAT),
      ]);

    auth()->logout();

    return response()->json([
      'message' => 'Su sesión ha sido finalizada exitosamente',
    ], 200);
  }

  public function refreshToken()
  {
    $validator = Validator::make(request()->all(), [
      'refresh_token' => 'required|string',
    ], [
      'refresh_token.required' => 'El token de refresco es obligatorio.',
      'refresh_token.string'   => 'El token de refresco debe ser una cadena de texto.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Parámetros inválidos.',
      ], 422);
    }

    $refreshToken = request()->get('refresh_token');
    $refreshTokenSesion = AuthUsuarioSesion::where('token', $refreshToken)
      ->where('id_estado', 1)
      ->first();

    if (!$refreshTokenSesion) {
      return response()->json([
        'message' => 'Token inválido o expirado',
      ], 400);
    }

    AuthUsuarioSesion::where('id_estado', 1)
      ->where('expires_at', '<=', Carbon::now())
      ->update([
        'id_estado' => 2,
        'closed_at' => date(DATE_FORMAT),
      ]);

    $usuario = AuthUsuario::where('id', $refreshTokenSesion->id_usuario)->first();

    $claims = [];

    $token = JWTAuth::claims($claims)->fromUser($usuario);

    $newRefreshToken = $this->generarRefreshToken($usuario);

    return $this->respondWithToken($token, $newRefreshToken);
  }

  public function enviarEnlaceRestablecimiento()
  {
    $validator = Validator::make(request()->all(), [
      'email' => 'required|email|exists:auth_usuario,email',
    ], [
      'email.required' => 'El campo de correo electrónico es obligatorio.',
      'email.email'    => 'El correo debe ser una dirección de correo válida.',
      'email.exists'   => 'El correo proporcionado no existe en nuestros registros.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Parámetros inválidos',
      ], 422);
    }

    $email = request()->get('email');

    try {
      DB::beginTransaction();

      $usuario = AuthUsuario::where('email', $email)->first();
      $token = Str::random(60);
      $usuario->remember_token = $token;
      $usuario->save();

      $mailData = [
        'title'       => 'Restablecimiento de contraseña',
        'body'        => 'Haga clic en el siguiente enlace para restablecer su contraseña:',
        'greeting'    => "Hola,",
        'actionUrl'   => url("/password/reset/{$token}"),
        'actionText'  => 'Restablecer contraseña:',
      ];

      Mail::to($email)->send(new SendMail($mailData));

      DB::commit();
      return response()->json([
        'message' => 'Enlace de restablecimiento enviado',
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'message' => 'Error enviando el enlace',
        'error' => $e->getMessage(),
        'linea' => $e->getLine(),
      ], 500);
    }
  }

  protected function respondWithToken($token, $refresh = null)
  {
    return response()->json([
      'token' => $token,
      'token_type' => 'bearer',
      'expires_in' => intval(JWTAuth::factory()->getTTL()),
      'refresh_token' => $refresh
    ]);
  }

  private function generarRefreshToken($user)
  {
    $refreshToken = Str::uuid()->toString();
    $minutesToExpires = env('REFRESH_TOKEN_TTL', 60);
    $now = Carbon::now();
    $exp = $now->addMinutes($minutesToExpires);

    AuthUsuarioSesion::create([
      'id_usuario' => $user->id,
      'token' => $refreshToken,
      'ip' => request()->ip(),
      'plataforma' => request()->header('User-Agent'),
      'id_estado' => 1,
      'expires_at' => $exp,
    ]);

    return $refreshToken;
  }

  public function verificarEmail()
  {
    $data = request()->only(['token']);

    $validator = Validator::make($data, [
      'token' => 'required',
    ], [
      'token.required' => 'El token es obligatorio.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }

    $user = AuthUsuario::where('remember_token', $data['token'])->first();

    if (!$user) {
      return response()->json([
        'message' => 'Token de verificación no válido'
      ], 422);
    }

    $user->verificado = true;
    $user->remember_token = null;
    $user->fecha_verificacion = now();
    $user->save();

    return response()->json([
      'message' => 'Correo verificado exitosamente'
    ], 200);
  }

  public function restablecerPassword()
  {
    $validator = Validator::make(request()->all(), [
      'email'    => 'required|email|exists:auth_usuario,email',
      'token'    => 'required|string',
      'password' => 'required|string|min:6|max:20|confirmed',
    ], [
      'email.required'    => 'El campo de correo electrónico es obligatorio.',
      'email.email'       => 'El correo debe ser una dirección de correo válida.',
      'email.exists'      => 'El correo proporcionado no existe en nuestros registros.',
      'token.required'    => 'El token es obligatorio.',
      'password.required' => 'El campo de contraseña es obligatorio.',
      'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
      'password.max'      => 'La contraseña no puede tener más de 20 caracteres.',
      'password.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => $validator->errors()->first(),
      ], 422);
    }

    $email    = request()->get('email');
    $token    = request()->get('token');
    $password = request()->get('password');

    $usuario = AuthUsuario::where('email', $email)->where('remember_token', $token)->first();

    if (!$usuario) {
      return response()->json([
        'message' => 'Token inválido o expirado',
      ], 422);
    }

    try {
      $usuario->password = Hash::make($password);
      $usuario->remember_token = null;
      $usuario->save();

      $message = 'Contraseña restablecida correctamente';
      $code = 200;
    } catch (\Exception $e) {
      $message = 'Error restableciendo la contraseña';
      $code = 400;
    }

    return response()->json([
      'message' => $message,
    ], $code);
  }
}
