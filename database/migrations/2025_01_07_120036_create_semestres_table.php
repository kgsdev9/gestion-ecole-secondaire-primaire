<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemestresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semestres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('annee_academique_id');
            $table->string('name');
            $table->boolean('cloture')->default(false);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamps();
            $table->foreign('annee_academique_id')->references('id')->on('annee_academiques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('semestres');
    }
}
