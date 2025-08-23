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
        Schema::create('orden_trabajo_referencias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orden_trabajo_id')->constrained('orden_trabajos')->onDelete('cascade');
            $table->foreignId('pedido_referencia_id')->constrained('pedido_referencia')->onDelete('cascade');

            $table->unsignedInteger('cantidad')->default(1);
            $table->unsignedInteger('cantidad_recibida')->nullable();

            $table->string('estado')->default('#FF0000');
            $table->boolean('recibido')->default(false);

            $table->date('fecha_recepcion')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajo_referencias');
    }
};
