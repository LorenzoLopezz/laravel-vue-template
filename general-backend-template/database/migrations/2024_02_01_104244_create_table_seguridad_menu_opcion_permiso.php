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
    Schema::create('seguridad_menu_opcion_permiso', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->timestamps();
      $table->foreignId('id_permiso')->constrained('seguridad_permiso')->nullable();
      $table->foreignId('id_menu_opcion')->constrained('seguridad_menu_opcion');
      $table->unique(['id_permiso', 'id_menu_opcion']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_menu_opcion_permiso');
  }
};
