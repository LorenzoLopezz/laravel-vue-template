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
    Schema::create('auth_usuario_permiso', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->foreignId('id_usuario')->constrained('auth_usuario');
      $table->foreignId('id_permiso')->constrained('seguridad_permiso');
      $table->foreignId('creado_por')->constrained('auth_usuario');
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable();
      $table->unique(['id_usuario', 'id_permiso']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('auth_usuario_permiso');
  }
};
