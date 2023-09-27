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
        Schema::create('conexions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("peso");
            $table->unsignedBigInteger('ruta')->nullable();
            $table->foreign('ruta')->references('id')->on('rutas');
            $table->unsignedBigInteger('nodo_a');
            $table->foreign('nodo_a')->references('id')->on('ubicacions');
            $table->unsignedBigInteger('nodo_b');
            $table->foreign('nodo_b')->references('id')->on('ubicacions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conexions');
    }
};
