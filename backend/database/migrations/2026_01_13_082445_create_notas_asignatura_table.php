<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notas_asignatura', function (Blueprint $table) {
            $table->id();
            $table->decimal('nota', 4, 2)->nullable();
            $table->year('anio');
            $table->foreignId('alumno_id')
                ->constrained('alumnos')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('asignatura_id')
                ->constrained('asignaturas')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['alumno_id', 'asignatura_id', 'anio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notas_asignatura');
    }
};
