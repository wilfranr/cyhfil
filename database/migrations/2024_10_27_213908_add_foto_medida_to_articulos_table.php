<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('articulos', function (Blueprint $table) {
        $table->string('foto_medida')->nullable()->after('definicion'); // Coloca el campo después de 'definicion' o donde prefieras
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            //
        });
    }
};
