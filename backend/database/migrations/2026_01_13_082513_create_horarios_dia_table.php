<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('horarios_dia', function (Blueprint $table) {
            $table->id();
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes']);
            $table->foreignId('estancia_id')
                ->constrained('estancias')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['estancia_id', 'dia_semana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('horarios_dia');
    }
};
