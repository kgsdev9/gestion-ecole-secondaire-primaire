<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyenneExamenLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moyenne_examen_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
            $table->decimal('moyenne', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['eleve_id', 'matiere_id', 'examen_id', 'anneeacademique_id'], 'unique_moyenne_examen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moyenne_examen_lignes');
    }
}
