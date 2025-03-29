<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pedido_referencia', function (Blueprint $table) {
            $table->integer('estado')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedido_referencia', function (Blueprint $table) {
            $table->integer('estado')->nullable()->default(null)->change();
        });
    }
};