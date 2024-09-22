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
        Schema::create('cuartosalquiler', function (Blueprint $table) {
            $table->id();
            $table->uuid('publicId');
            $table->unsignedBigInteger('cuartoId');
            $table->string('descripcion_alquiler')->nullable();
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida')->nullable();
            $table->unsignedBigInteger('total_minutos')->nullable();
            $table->float('total_pagado')->nullable();
            $table->timestamps();
            $table->dateTime('fecha_eliminado')->nullable();
            $table->boolean('ticket_impreso')->default(false);
            $table->boolean('ticket_reimpreso')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuartosalquiler');
    }
};
