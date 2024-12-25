<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_clients', function (Blueprint $table) {
            $table->id();
            $table->string('codeclient')->unique();
            $table->string('libtiers');
            $table->string('adressepostale')->nullable();
            $table->string('adressegeo')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('numerocomtribuabe')->nullable();
            $table->string('numerodecompte')->nullable();
            $table->decimal('capital', 15, 2)->nullable(); // Format pour les montants financiers
            $table->foreignId('tregimefiscal_id')->nullable()->constrained('t_regime_fiscals')->onDelete('cascade');
            $table->foreignId('tcodedevise_id')->nullable()->constrained('t_code_devises')->onDelete('cascade');
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
        Schema::dropIfExists('t_clients');
    }
}
