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
        Schema::create('cancelaciones', function (Blueprint $table) {
            $table->id();
            $table->uuid('publicId');
            $table->unsignedBigInteger('cuartoAlquilerId');
            $table->unsignedBigInteger('usuarioId');
            $table->dateTime('fecha_cancelacion');
            $table->string('motivo_cancelacion');
            $table->boolean('aprobado')->default(false);
            $table->unsignedBigInteger('aprobadoPorId')->nullable();
            $table->dateTime('fechaAprobacion')->nullable();
            $table->date('fecha_eliminado')->nullable();
            $table->string('nota_aprobacion')->nullable();
            $table->timestamps();

            $table->foreign('cuartoAlquilerId')->references('id')->on('cuartosalquiler');
            $table->foreign('usuarioId')->references('id')->on('usuarios');
            $table->foreign('aprobadoPorId')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancelaciones');
    }
};
