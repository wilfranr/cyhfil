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
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            // Eliminar el campo Entrega si existe
            if (Schema::hasColumn('pedido_referencia_proveedor', 'Entrega')) {
                $table->dropColumn('Entrega');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            // Restaurar el campo Entrega si se revierte la migración
            $table->string('Entrega')->nullable();
        });
    }
};
