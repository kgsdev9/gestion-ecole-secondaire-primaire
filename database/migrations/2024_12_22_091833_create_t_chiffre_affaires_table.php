<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTChiffreAffairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_chiffre_affaires', function (Blueprint $table) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();  // Identifiant unique de la livraison
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Référence à la commande
                $table->text('delivery_address'); // Adresse de livraison
                $table->string('status'); // Statut de la livraison (En attente, En cours, Livré)
                $table->timestamp('delivery_date')->nullable(); // Date prévue pour la livraison
                $table->timestamp('actual_delivery_date')->nullable(); // Date réelle de livraison
                $table->string('carrier'); // Transporteur (DHL, UPS, etc.)
                $table->string('tracking_number')->nullable(); // Numéro de suivi
                $table->decimal('shipping_cost', 10, 2); // Coût de la livraison
                $table->timestamps(); // Champs created_at et updated_at
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_chiffre_affaires');
    }
}
