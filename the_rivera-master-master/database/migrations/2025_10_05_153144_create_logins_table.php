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
        Schema::create('logins', function (Blueprint $table) {
            $table->id();

             // Relación  con cliente
            $table->unsignedBigInteger('cliente_id');
            //$table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');

            // Relación  con empleado
            $table->unsignedBigInteger('empleado_id');
            //$table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');

            // Rol del usuario
            $table->enum('rol', ['cliente', 'empleado', 'administrador']);

            // Datos de login
            $table->string('usuario')->unique();
            $table->string('password');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logins');
    }
};
