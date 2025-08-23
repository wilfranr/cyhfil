<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMotivoCancelacionToOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            // Agregar la columna 'motivo_cancelacion' de tipo texto (nullable)
            $table->text('motivo_cancelacion')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            // Eliminar la columna 'motivo_cancelacion' en caso de rollback
            $table->dropColumn('motivo_cancelacion');
        });
    }
}
