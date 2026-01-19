<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notas_competencia_tec', function (Blueprint $table) {
            $table->id();
            $table->decimal('nota', 4, 2)->nullable();
            $table->foreignId('competencia_tec_id')
                ->constrained('competencias_tec')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('estancia_id')
                ->constrained('estancias')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['estancia_id', 'competencia_tec_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notas_competencia_tec');
    }
};
