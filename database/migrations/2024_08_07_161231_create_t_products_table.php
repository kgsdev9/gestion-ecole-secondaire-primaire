<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_products', function (Blueprint $table) {
            $table->id();
            $table->string('libelleproduct');
            $table->string('prixachat');
            $table->string('prixvente');
            $table->string('codeproduct')->nullable();
            $table->string('qtedisponible')->nullable();
            $table->foreignId('tcategorieproduct_id')->constrained('t_categorie_products')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('t_products');
    }
}
