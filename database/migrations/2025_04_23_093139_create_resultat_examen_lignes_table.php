<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultatExamenLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultat_examen_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('resultat_examen_id')->constrained('resultat_examens')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->integer('nombre_total_points');
            $table->decimal('moyenne', 5, 2)->nullable();
            $table->boolean('admis')->default(false);
            $table->string('mention')->nullable();
            $table->integer('rang')->nullable();
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
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
        Schema::dropIfExists('resultat_examen_lignes');
    }
}
