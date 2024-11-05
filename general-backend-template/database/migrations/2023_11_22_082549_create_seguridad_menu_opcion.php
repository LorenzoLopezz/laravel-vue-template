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
    Schema::create('seguridad_menu_opcion', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->string('nombre', 100);
      $table->string('nombre_unico', 100)->unique()->nullable(false);
      $table->string('icono', 255)->nullable();
      $table->foreignId('id_modulo')->nullable()->constrained('seguridad_modulo');
      $table->boolean('mostrar_menu')->defaultValue(false);
      $table->boolean('requiere_autenticacion')->defaultValue(false);
      $table->boolean('enrutable')->defaultValue(true);
      $table->string('uri', 255)->nullable();
      $table->string('componente', 255)->nullable();
      $table->boolean('dependencia_menu_padre')->default(false);
      $table->foreignId('id_menu_opcion_padre')->nullable()->constrained('seguridad_menu_opcion');
      $table->foreignId('id_estado')->constrained('estado')->defaultValue(1);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('seguridad_menu_opcion');
  }
};
