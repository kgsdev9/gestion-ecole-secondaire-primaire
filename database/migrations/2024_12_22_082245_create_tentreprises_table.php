<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTentreprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tentreprises', function (Blueprint $table) {
            $table->id();
            $table->string('libtiers');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('fax')->nullable();
            $table->string('logo')->nullable();
            $table->string('numero_registre')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('tentreprises');
    }
}
