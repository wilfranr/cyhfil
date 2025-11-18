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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros')->onDelete('cascade');
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('terceros')->onDelete('cascade');
            $table->enum('estado', ['Pendiente', 'Aprobada', 'Rechazada', 'Completada'])->default('Pendiente');
            $table->date('fecha_expedicion');
            $table->date('fecha_entrega');
            $table->text('observaciones')->nullable();
            $table->integer('cantidad')->default(0);
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->decimal('valor_unitario', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->decimal('valor_iva', 10, 2)->default(0);
            $table->decimal('valor_descuento', 10, 2)->default(0);
            $table->string('guia')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
            
            // Agregar índices para mejorar el rendimiento
            $table->index(['proveedor_id', 'estado']);
            $table->index(['pedido_id']);
            $table->index(['cotizacion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compras');
    }
};
