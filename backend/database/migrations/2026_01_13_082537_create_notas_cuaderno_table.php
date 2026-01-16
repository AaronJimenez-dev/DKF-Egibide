<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notas_cuaderno', function (Blueprint $table) {
            $table->id();
            $table->decimal('nota', 4, 2)->nullable();
            $table->foreignId('cuaderno_practicas_id')
                ->constrained('cuadernos_practicas')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['cuaderno_practicas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notas_cuaderno');
    }
};
