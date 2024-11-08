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
    Schema::create('estado', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->string('nombre');
      $table->foreignId('id_calificador')->constrained('estado_calificador');
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
    Schema::dropIfExists('estado');
  }
};
