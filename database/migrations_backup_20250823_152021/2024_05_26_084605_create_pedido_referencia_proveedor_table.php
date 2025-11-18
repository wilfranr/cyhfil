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
        Schema::create('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referencia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tercero_id')->constrained()->cascadeOnDelete();
            $table->foreignId('marca_id')->constrained()->cascadeOnDelete();
            $table->integer('dias_entrega');
            $table->decimal('costo_unidad', 10, 2);
            $table->decimal('utilidad', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_referencia_proveedor');
    }
};
