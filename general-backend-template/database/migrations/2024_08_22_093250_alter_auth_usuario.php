<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('auth_usuario', function (Blueprint $table) {
      $table->string('primer_nombre');
      $table->string('segundo_nombre')->nullable();
      $table->string('primer_apellido');
      $table->string('segundo_apellido')->nullable();
      $table->date('fecha_nacimiento');
      $table->string('justificacion_bloqueo')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('auth_usuario');
  }
};
