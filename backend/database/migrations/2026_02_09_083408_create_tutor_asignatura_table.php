<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla tutor_asignatura
 * 
 * Esta tabla relaciona a los tutores con las asignaturas que imparten.
 * Es necesaria para la importación de asignaciones.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tutor_asignatura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('asignatura_id');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('tutor_id')
                  ->references('id')
                  ->on('tutores')
                  ->onDelete('cascade');
                  
            $table->foreign('asignatura_id')
                  ->references('id')
                  ->on('asignaturas')
                  ->onDelete('cascade');
            
            // Índice único para evitar duplicados
            $table->unique(['tutor_id', 'asignatura_id']);
            
            // Índices para búsquedas
            $table->index('tutor_id');
            $table->index('asignatura_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_asignatura');
    }
};