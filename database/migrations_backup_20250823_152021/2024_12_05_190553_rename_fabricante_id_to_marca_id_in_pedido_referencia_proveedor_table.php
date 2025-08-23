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
            $table->renameColumn('fabricante_id', 'marca_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->renameColumn('marca_id', 'fabricante_id');
        });
    }
};
