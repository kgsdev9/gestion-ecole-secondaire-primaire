<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScolaritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scolarites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_niveau')->constrained('niveaux')->onDelete('cascade');
            $table->foreignId('id_classe')->constrained('classes')->onDelete('cascade');
            $table->foreignId('id_annee_academique')->constrained('annee_academiques')->onDelete('cascade');
            $table->decimal('montant_scolarite', 10, 2);
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
        Schema::dropIfExists('scolarites');
    }
}
