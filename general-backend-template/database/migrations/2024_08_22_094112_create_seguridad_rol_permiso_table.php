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
    Schema::create('seguridad_rol_permiso', function (Blueprint $table) {
      $table->id();
      $table->foreignId('id_seguridad_permiso')->constrained('seguridad_permiso');
      $table->foreignId('id_rol')->constrained('seguridad_rol');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_rol_permiso');
  }
};