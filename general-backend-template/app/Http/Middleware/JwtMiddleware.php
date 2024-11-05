<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions;
use Illuminate\Http\Request;

class JwtMiddleware
{
  public function handle(Request $request, Closure $next)
  {
    $error = true;
    $message = 'Se requiere un token de sesión para acceder a este recurso';

    try {
      $user = JWTAuth::parseToken()->authenticate();
      if ($user) {
        $error = false;
      }
    } catch (Exceptions\TokenInvalidException $e) {
      $message = 'Ha proporcionado un token de sesión inválido';
    } catch (Exceptions\TokenExpiredException $e) {
      $message = 'Su token de sesión ha expirado';
    } catch (Exception $e) {
      $message = 'Debe proporcionar un token de sesión válido';
    }

    return ($error) ? response()->json([ 'message' => $message ], 401) : $next($request);
  }
}
