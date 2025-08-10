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
        Schema::table('referencias', function (Blueprint $table) {
            // Drop existing foreign key to allow altering the column
            try {
                $table->dropForeign(['articulo_id']);
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            // Make articulo_id nullable
            $table->unsignedBigInteger('articulo_id')->nullable()->change();

            // Recreate FK pointing to articulos, and set null on delete to preserve referencias
            $table->foreign('articulo_id')
                ->references('id')
                ->on('articulos')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referencias', function (Blueprint $table) {
            // Drop the current foreign key
            try {
                $table->dropForeign(['articulo_id']);
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            // Make articulo_id NOT NULL again (WARNING: will fail if there are NULLs)
            $table->unsignedBigInteger('articulo_id')->nullable(false)->change();

            // Restore original FK behavior (cascade)
            $table->foreign('articulo_id')
                ->references('id')
                ->on('articulos')
                ->onDelete('cascade');
        });
    }
};
