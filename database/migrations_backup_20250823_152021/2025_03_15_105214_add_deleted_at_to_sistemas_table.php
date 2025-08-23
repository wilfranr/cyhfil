<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sistemas', function (Blueprint $table) {
            $table->softDeletes(); // Agrega la columna deleted_at
        });
    }

    public function down()
    {
        Schema::table('sistemas', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};