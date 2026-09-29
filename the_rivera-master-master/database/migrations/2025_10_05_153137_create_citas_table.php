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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            // Relaciones con otras tablas
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('cliente_id');
            //$table->unsignedBigInteger('servicio_id');

            //Claves foraneas
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            //$table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->dateTime('fecha_hora');            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
