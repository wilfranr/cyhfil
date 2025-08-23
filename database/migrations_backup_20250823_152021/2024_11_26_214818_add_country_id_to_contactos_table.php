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
        Schema::table('contactos', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->after('indicativo'); // Campo country_id que puede ser nulo
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null'); // Clave foránea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->dropForeign(['country_id']); // Eliminar la clave foránea
            $table->dropColumn('country_id'); // Eliminar el campo
        });
    }
};

