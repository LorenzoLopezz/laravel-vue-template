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
    Schema::create('auth_usuario', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->uuid('codigo')->unique();
      $table->string('username');
      $table->string('email')->unique();
      $table->string('password');
      $table->boolean('verificado')->defaultValue(false);
      $table->timestamp('fecha_verificacion')->nullable();
      $table->foreignId('id_estado')->constrained('estado')->defaultValue(1);
      $table->foreignId('creado_por')->nullable()->constrained('auth_usuario');
      $table->timestamp('ultima_sesion')->nullable();
      $table->rememberToken();
      $table->timestamp('created_at')->useCurrent();
      $table->timestamp('updated_at')->nullable();
      $table->softDeletes();
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
