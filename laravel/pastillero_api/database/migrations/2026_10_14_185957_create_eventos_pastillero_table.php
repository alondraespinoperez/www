<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_pastillero', function (Blueprint $table) {
            $table->id();
            $table->string('id_pastillero');
            $table->integer('compartimento');
            $table->string('tipo'); // abierto, cerrado, error, dispensado
            $table->timestamps();

            $table->foreign('id_pastillero')
                ->references('id_pastillero')
                ->on('dispositivos')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_pastillero');
    }
};