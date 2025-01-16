<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploiDuTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matiere_id');
            $table->time('heure_debut');
            $table->time('heure_fin'); 
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('jour_id');
            $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('jour_id')->references('id')->on('jours')->onDelete('cascade');
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
        Schema::dropIfExists('emploi_du_temps');
    }
}
