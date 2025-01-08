<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_eleve')->constrained('eleves')->onDelete('cascade'); // ID de l'élève (clé étrangère)
            $table->foreignId('anneeacademique_id')->constrained()->onDelete('cascade'); // ID de l'année académique (clé étrangère)
            $table->foreignId('id_classe')->constrained('classes')->onDelete('cascade'); // ID de la classe (clé étrangère)
            $table->date('date_inscription');
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
        Schema::dropIfExists('inscriptions');
    }
}
