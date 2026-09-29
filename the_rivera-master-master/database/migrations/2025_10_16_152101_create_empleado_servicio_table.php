<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleado_servicio', function (Blueprint $table) {
            $table->id();

            // Relación con el empleado
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references ('id')->on('empleados')->onDelete('cascade');

        
            // Relación con el servicio
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleado_servicio');
    }
};
