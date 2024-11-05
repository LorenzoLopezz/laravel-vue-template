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
    Schema::create('seguridad_perfil_menu_opcion', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->foreignId('id_perfil')->constrained('seguridad_perfil')->nullable();
      $table->foreignId('id_menu_opcion')->constrained('seguridad_menu_opcion');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_perfil_menu_opcion');
  }
};
