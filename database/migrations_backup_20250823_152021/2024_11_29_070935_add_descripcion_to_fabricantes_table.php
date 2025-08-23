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
        Schema::table('fabricantes', function (Blueprint $table) {
            $table->string('descripcion', 500)->nullable()->after('nombre'); // Agrega el campo después de 'nombre'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fabricantes', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
