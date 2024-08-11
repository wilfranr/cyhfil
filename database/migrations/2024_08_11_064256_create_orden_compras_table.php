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
            $table->foreignId('tercero_id')->constrained();//NitProveedor
            $table->foreignId('pedido_id')->constrained();//IdPedido
            $table->foreignId('cotizaciones_id')->constrained();//IdCotizacion
            $table->string('estado');//Estado
            $table->foreign('referencia_id')->constrained();//IdReferencia
            $table->foreign('pedido_referencia_id')->constrained();//IdPedidoReferencia
            $table->date('fecha_expedicion');//Fecha en la que se genera la orden de compra
            $table->date('fecha_entrega');//Fecha en la que se espera la entrega de la orden de compra
            $table->text('observaciones');//Observaciones
            $table->integer('cantidad');//Cantidad
            $table->string('direccion');//Direccion
            $table->string('telefono');//Telefono
            $table->decimal('valor_unitario', 10, 2);//ValorUnitario
            $table->decimal('valor_total', 10, 2);//ValorTotal
            $table->decimal('valor_iva', 10, 2);//ValorIva
            $table->decimal('valor_descuento', 10, 2);//ValorDescuento
            $table->string('guia');//Guia
            $table->string('color');//Color
            $table->timestamps();
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
