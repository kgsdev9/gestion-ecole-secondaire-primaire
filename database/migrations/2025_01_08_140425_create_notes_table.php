<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_eleve')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('id_matiere')->constrained('matieres')->onDelete('cascade');
            $table->decimal('note', 5, 2);
            $table->enum('type_composition', ['Contrôle continu', 'Examen', 'Devoir', 'Autre']);
            $table->date('date_composition');
            $table->foreignId('anneacademique_id')->constrained('annee_academiques')->onDelete('cascade');
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
        Schema::dropIfExists('notes');
    }
}
