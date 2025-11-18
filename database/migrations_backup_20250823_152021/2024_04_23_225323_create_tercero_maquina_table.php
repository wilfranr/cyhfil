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
        Schema::create('tercero_maquina', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tercero_id');
            $table->unsignedBigInteger('maquina_id');
            $table->timestamps();

            $table->foreign('tercero_id')->references('id')->on('terceros')->onDelete('cascade');
            $table->foreign('maquina_id')->references('id')->on('maquinas')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tercero_maquina');
    }
};
