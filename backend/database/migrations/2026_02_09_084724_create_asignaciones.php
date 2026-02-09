<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla asignaciones: Guarda la asignación de un tutor a una asignatura
 * con toda la información del grupo y contexto
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('tutor_id')
                ->constrained('tutores')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            
            $table->foreignId('asignatura_id')
                ->constrained('asignaturas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            
            $table->foreignId('ciclo_id')
                ->constrained('ciclos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            
            // Información del grupo
            $table->string('campus')->nullable();
            $table->string('grupo'); // Código del grupo (ej: 131DA)
            $table->string('modelo')->nullable(); // Modelo educativo (A, B, etc.)
            $table->string('regimen')->nullable(); // D (Diurno), etc.
            $table->text('descripcion_grupo')->nullable(); // Descripción completa
            
            $table->timestamps();
            
            // Índices
            $table->index('tutor_id');
            $table->index('asignatura_id');
            $table->index('ciclo_id');
            $table->index('grupo');
            
            // Un tutor no puede tener la misma asignatura duplicada en el mismo grupo
            $table->unique(['tutor_id', 'asignatura_id', 'grupo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};