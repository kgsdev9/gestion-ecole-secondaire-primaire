<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfactureLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tfacture_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tproduct_id')->nullable()->constrained('t_products')->onDelete('cascade');
            $table->string('designation')->nullable();
            $table->string('codecommade')->nullable();
            $table->string('codefacture')->nullable();
            $table->string('numvente')->nullable();
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('remise', 5, 2)->default(0.00);
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
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
        Schema::dropIfExists('tfacture_lignes');
    }
}
