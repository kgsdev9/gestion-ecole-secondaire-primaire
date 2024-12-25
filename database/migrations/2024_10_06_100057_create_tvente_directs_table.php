<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTventeDirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvente_directs', function (Blueprint $table) {
            $table->id();
            $table->string('numvte');
            $table->string('codeclient');
            $table->string('nom');
            $table->string('prenom');
            $table->boolean('tvafacture')->default(false);
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->decimal('montantht', 15, 2);
            $table->decimal('montanttc', 15, 2);
            $table->decimal('montanttva', 15, 2)->nullable();
            $table->decimal('montantadsci', 15, 2)->nullable();
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
        Schema::dropIfExists('tvente_directs');
    }
}
