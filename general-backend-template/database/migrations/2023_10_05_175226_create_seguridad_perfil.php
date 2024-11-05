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
    Schema::create('seguridad_perfil', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->string('nombre', 200)->unique();
      $table->string('acronimo', 20)->unique();
      $table->foreignId('id_estado')->constrained('estado')->defaultValue(1);
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_perfil');
  }
};
