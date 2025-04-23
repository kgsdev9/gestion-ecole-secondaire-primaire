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
            $table->string('code')->unique();
            $table->string('title');
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('anneeacademique_id')->constrained('annee_academiques')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moyenne_examens');
    }
}


