<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTventeDirectLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvente_direct_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('numvte');
            $table->string('reference')->nullable();
            $table->foreignId('tproduct_id')->nullable()->constrained('t_products')->onDelete('cascade');
            $table->integer('qte');
            $table->decimal('prixunitaire', 15, 2);
            $table->decimal('remiseligne', 15, 2)->nullable();
            $table->decimal('montantht', 15, 2);
            $table->decimal('montanttva', 15, 2);
            $table->decimal('montantttc', 15, 2);
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
        Schema::dropIfExists('tvente_direct_lignes');
    }
}
