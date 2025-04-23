<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapportSemestreLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapport_semestre_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_semestre_id')->constrained('rapport_semestres')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->decimal('moyenne', 5, 2)->nullable();
            $table->integer('rang')->nullable();
            $table->string('mention')->nullable();
            $table->boolean('admis')->default(false);
            $table->text('observation')->nullable();
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
        Schema::dropIfExists('rapport_semestre_lignes');
    }
}
