<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoyenneExamensTable extends Migration
{
    public function up(): void
    {
        Schema::create('moyenne_examens', function (Blueprint $table) {
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

    public function down(): void
    {
        Schema::dropIfExists('moyenne_examens');
    }
}
