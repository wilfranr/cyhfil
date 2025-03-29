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
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->unsignedBigInteger('direccion_id')->nullable()->after('fecha_entrega');
            $table->foreign('direccion_id')->references('id')->on('direcciones');
            $table->dropColumn('direccion'); // elimina el campo de texto
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropForeign(['direccion_id']);
            $table->dropColumn('direccion_id');
            $table->string('direccion')->nullable()->after('fecha_entrega'); // vuelve a agregar el campo de texto
        });
    }
};