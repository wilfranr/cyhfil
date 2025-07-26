<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePedidoIdToPedidoReferenciaIdInPedidoReferenciaProveedorTable extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->renameColumn('pedido_id', 'pedido_referencia_id');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_referencia_proveedor', function (Blueprint $table) {
            $table->renameColumn('pedido_referencia_id', 'pedido_id');
        });
    }
}