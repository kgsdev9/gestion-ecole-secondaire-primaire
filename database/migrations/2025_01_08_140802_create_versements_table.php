<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->unsignedBigInteger('anneeacademique_id');
            $table->unsignedBigInteger('scolarite_id')->nullable();
            $table->foreignId('typeversement_id')->constrained('type_versements')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->decimal('montant_verse', 10, 2);
            $table->decimal('montant_restant', 10, 2);
            $table->date('date_versement')->nullable();
            $table->foreign('anneeacademique_id')->references('id')->on('annee_academiques')->onDelete('cascade');
            $table->foreign('scolarite_id')->references('id')->on('scolarites')->onDelete('cascade');
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
        Schema::dropIfExists('versements');
    }
}
