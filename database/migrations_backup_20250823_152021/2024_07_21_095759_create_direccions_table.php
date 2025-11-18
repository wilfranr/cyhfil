<?php

use App\Models\Country;
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
        Schema::create('direccions', function (Blueprint $table) {
            $table->id();
            $table->string('direccion');
            $table->string('city_id');
            $table->string('state_id');
            $table->string('country_id');
            $table->boolean('principal')->default(false);

            $table->foreignId('tercero_id')->constrained('terceros')->cascadeOnDelete();



            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccions');
    }
};
