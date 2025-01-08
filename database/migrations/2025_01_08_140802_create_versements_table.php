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
            $table->foreignId('id_eleve')->constrained('etudiants')->onDelete('cascade');
            $table->decimal('montant', 10, 2); // Montant du versement
            $table->date('date_versement'); // Date du versement
            $table->enum('type_versement', ['Frais d\'inscription', 'Frais de scolarité', 'Examen', 'Autres']); // Type du versement
            $table->enum('statut_versement', ['Payé', 'Non payé', 'En retard']); // Statut du versement
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
