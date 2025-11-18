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
        Schema::table('direcciones', function (Blueprint $table) {
            $table->string('destinatario')->after('tercero_id');
            $table->string('nit_cc')->after('destinatario');
            $table->foreignId('transportadora_id')->nullable()->after('nit_cc')->constrained('transportadoras')->nullOnDelete();
            $table->string('forma_pago')->after('transportadora_id');
            $table->string('telefono')->after('forma_pago');
            // Nota: Ya existe city_id, pero agregamos ciudad como texto por si acaso
            $table->string('ciudad_texto')->nullable()->after('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('direcciones', function (Blueprint $table) {
            $table->dropForeign(['transportadora_id']);
            $table->dropColumn([
                'destinatario',
                'nit_cc',
                'transportadora_id',
                'forma_pago',
                'telefono',
                'ciudad_texto'
            ]);
        });
    }
};
