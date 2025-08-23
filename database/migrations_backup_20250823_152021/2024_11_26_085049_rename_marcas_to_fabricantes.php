<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('marcas', 'fabricantes');

        
        Schema::table('tercero_marcas', function (Blueprint $table) {
            $table->renameColumn('marca_id', 'fabricante_id');
        });
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->renameColumn('marca_id', 'fabricante_id');
        });
    }

    public function down(): void
    {
        // Revertir cambios
        Schema::rename('fabricantes', 'marcas');

        Schema::table('maquinas', function (Blueprint $table) {
            $table->renameColumn('fabricante_id', 'marca_id');
        });
        Schema::table('tercero_marcas', function (Blueprint $table) {
            $table->renameColumn('fabricante_id', 'marca_id');
        });
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->renameColumn('fabricante_id', 'marca_id');
        });
    }
};
