<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepartitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repartitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anneeacademique_id');
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreign('anneeacademique_id')->references('id')->on('annee_academiques')->onDelete('cascade');
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
        Schema::dropIfExists('repartitions');
    }
}
