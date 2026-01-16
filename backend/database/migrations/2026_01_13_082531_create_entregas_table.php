<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->string('archivo', 255);
            $table->date('fecha');
            $table->foreignId('cuaderno_practicas_id')
                ->constrained('cuadernos_practicas')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('entregas');
    }
};
