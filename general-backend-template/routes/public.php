<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
  Route::post('/iniciar-sesion', [AuthController::class, 'iniciarSesion'])->name('login');
  Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
  Route::post('/verificar-email', [AuthController::class, 'verificarEmail']);
  // ruta de ejemplo para enviar el correo
  Route::get('/mail', [MailController::class, 'index']);
  Route::post('/recuperar-password', [AuthController::class, 'resetPassword']);
  Route::put('/usuario/password', [AuthController::class, 'restablecerPassword']);
  Route::post('/usuario/password/recuperacion', [AuthController::class, 'enviarEnlaceRestablecimiento']);
});
