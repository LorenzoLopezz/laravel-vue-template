<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * The list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->renderable(function (Throwable $e) {
      $code = 500;
      $message = $e->getMessage();

      if ($e instanceof \Illuminate\Validation\ValidationException) {
        $code = 422;
        $message = $e->validator->errors()->first();
      }

      if ($e instanceof \Illuminate\Auth\AuthenticationException) {
        $code = 401;
        $message = $e->getMessage();
      }

      if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        $code = 404;
        $message = 'La ruta solicitada no existe';
      }

      if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
        $code = 405;
        $message = 'El método de la petición no es válido';
      }

      if ($e instanceof \Illuminate\Database\QueryException) {
        $code = 500;
        $message = $e->getMessage();
      }

      return response()->json([
        'message' => $message,
      ], $code);
    });
  }
}
