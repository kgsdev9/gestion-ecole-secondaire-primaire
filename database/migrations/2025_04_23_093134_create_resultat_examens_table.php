<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatExamensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultat_examens', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
            $table->decimal('taux_reussite', 5, 2);
            $table->decimal('moyenne_examen', 5, 2)->nullable();
            $table->integer('nb_admis')->nullable();
            $table->integer('nb_total_participant')->nullable();
            $table->boolean('statut_publication')->default(false);
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
        Schema::dropIfExists('resultat_examens');
    }
}
