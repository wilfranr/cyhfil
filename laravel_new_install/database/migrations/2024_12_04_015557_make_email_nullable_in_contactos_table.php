<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('cargo')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contactos', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('cargo')->nullable(false)->change();
        });
    }
};
