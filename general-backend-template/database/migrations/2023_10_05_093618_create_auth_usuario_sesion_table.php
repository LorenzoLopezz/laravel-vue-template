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
    Schema::create('auth_usuario_sesion', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->foreignId('id_usuario')->constrained('auth_usuario');
      $table->string('token', 200);
      $table->string('ip', 20)->nullable();
      $table->string('plataforma')->nullable();
      $table->foreignId('id_estado')->constrained('estado')->defaultValue(1);
      $table->timestamp('expires_at')->nullable();
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable();
      $table->timestamp('closed_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('auth_usuario_sesion');
  }
};
