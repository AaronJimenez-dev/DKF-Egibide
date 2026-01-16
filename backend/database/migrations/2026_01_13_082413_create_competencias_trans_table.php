<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('competencias_trans', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->string('nivel', 50)->nullable();
            $table->foreignId('familia_profesional_id')
                ->constrained('familias_profesionales')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('competencias_trans');
    }
};
