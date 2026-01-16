<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('cuadernos_practicas', function (Blueprint $table) {
            $table->id();
            $table->string('archivo', 255)->nullable();
            $table->string('archivo_vacio', 255)->nullable();
            $table->foreignId('estancia_id')
                ->constrained('estancias')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['estancia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('cuadernos_practicas');
    }
};
