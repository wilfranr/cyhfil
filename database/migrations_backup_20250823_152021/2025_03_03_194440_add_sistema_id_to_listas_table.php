<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('listas', function (Blueprint $table) {
            $table->unsignedBigInteger('sistema_id')->nullable()->after('id');
            $table->foreign('sistema_id')->references('id')->on('sistemas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('listas', function (Blueprint $table) {
            $table->dropForeign(['sistema_id']);
            $table->dropColumn('sistema_id');
        });
    }
};