<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->unsignedBigInteger('classe_id');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('cloture')->default(false);
            $table->timestamps();
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
            $table->foreignId('typeexamen_id')->constrained('type_examens')->onDelete('cascade');
            $table->foreign('affectationacademique_id')->references('id')->on('affection_academiques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examens');
    }
}
