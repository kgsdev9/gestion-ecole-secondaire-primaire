<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasculementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basculements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade'); // L'élève qui change d'année
            $table->unsignedBigInteger('ancienne_anneeacademique_id');
            $table->foreign('ancienne_anneeacademique_id')->references('id')->on('annee_academiques')->onDelete('cascade'); // L'année académique précédente
            $table->unsignedBigInteger('nouvelle_anneeacademique_id');
            $table->foreign('nouvelle_anneeacademique_id')->references('id')->on('annee_academiques')->onDelete('cascade'); // L'année académique suivante
            $table->unsignedBigInteger('ancienne_classe_id');
            $table->foreign('ancienne_classe_id')->references('id')->on('classes')->onDelete('cascade'); // La classe précédente de l'élève
            $table->unsignedBigInteger('nouvelle_classe_id');
            $table->foreign('nouvelle_classe_id')->references('id')->on('classes')->onDelete('cascade'); // La classe suivante de l'élève
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
        Schema::dropIfExists('basculements');
    }
}
