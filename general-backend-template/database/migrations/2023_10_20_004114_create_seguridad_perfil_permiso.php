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
    Schema::create('seguridad_perfil_permiso', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->foreignId('id_perfil')->constrained('seguridad_perfil');
      $table->foreignId('id_permiso')->constrained('seguridad_permiso');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_perfil_permiso');
  }
};
