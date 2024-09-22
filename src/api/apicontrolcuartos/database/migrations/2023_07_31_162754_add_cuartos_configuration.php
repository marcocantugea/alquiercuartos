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
        Schema::create('cuartosconfiguracion', function (Blueprint $table) {
            $table->id();
            $table->uuid('publicId');
            $table->string('nombre')->max(35);
            $table->string('valor')->max(35);
            $table->timestamps();
            $table->dateTime('fecha_eliminado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuartosconfiguracion');
    }
};
