<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeExamenLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_examen_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('matiere_id');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('duree')->nullable();
            $table->string('jour')->nullable();
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
            $table->foreign('examen_id')->references('id')->on('examens')->onDelete('cascade');
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
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
        Schema::dropIfExists('programme_examen_lignes');
    }
}
