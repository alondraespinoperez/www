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
        Schema::create('cita_empleado_cliente_servicio', function (Blueprint $table) {
            $table->id();

            // Relación con la cita
            $table->unsignedBigInteger('cita_id');
            $table->foreign('cita_id')->references ('id')->on('citas')->onDelete('cascade');

            // Relación con el cliente (user con rol cliente)
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

            // Relación con el empleado (user con rol empleado)
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');

            // Relación con el servicio
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita_empleado_cliente_servicio');
    }
};
