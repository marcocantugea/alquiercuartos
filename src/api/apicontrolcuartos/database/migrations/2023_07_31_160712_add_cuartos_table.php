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
        Schema::create('cuartos', function (Blueprint $table) {
            $table->id();
            $table->uuid('publicId');
            $table->string('codigo')->max(20);
            $table->string('descripcion')->max(100)->nullable();
            $table->unsignedBigInteger('estatusId')->default(1);
            $table->timestamps();
            $table->dateTime('fecha_eliminado')->nullable();

            $table->foreign('estatusId')->references('id')->on('cuartoestatus');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuartos');
    }
};
