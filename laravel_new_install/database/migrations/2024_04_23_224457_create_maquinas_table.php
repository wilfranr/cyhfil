<?php

use App\Models\Pedido;
use App\Models\Tercero;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMaquinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maquinas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('serie')->unique();
            $table->text('arreglo')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('fotoId')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maquinas');
    }
}