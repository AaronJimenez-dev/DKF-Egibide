<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('cif', 20)->unique();
            $table->string('nombre', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('empresas');
    }
};
