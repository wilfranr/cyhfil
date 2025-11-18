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
        Schema::create('tercero_contacto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained();
            $table->string('nombre');
            $table->string('cargo');
            $table->string('telefono');
            $table->string('email');
            $table->boolean('principal')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tercero_contacto');
    }
};
