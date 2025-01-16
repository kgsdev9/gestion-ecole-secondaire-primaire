<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyennesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moyennes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('matiere_id');
            $table->unsignedBigInteger('semestre_id')->nullable();
            $table->unsignedBigInteger('annee_academique_id');
            $table->float('moyenne', 5, 2);
            $table->timestamps();
            $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
            $table->foreign('semestre_id')->references('id')->on('semestres')->onDelete('cascade');
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
        Schema::dropIfExists('moyennes');
    }
}
