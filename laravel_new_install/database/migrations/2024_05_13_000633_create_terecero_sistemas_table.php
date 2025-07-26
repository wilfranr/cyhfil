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
        Schema::create('terecero_sistemas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sistema_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terecero_sistemas');
    }
};
