<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('ciclos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('grupo',6)->unique()->nullable();
            $table->foreignId('familia_profesional_id')
                ->constrained('familias_profesionales')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->unique(['familia_profesional_id', 'nombre']);
            $table->text('descripcion')->nullable();
            $table->string('modelo', 10)->nullable();
            $table->string('regimen', 10)->nullable();
            
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('ciclos');
    }
};
