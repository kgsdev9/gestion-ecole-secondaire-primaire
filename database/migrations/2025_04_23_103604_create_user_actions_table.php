<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID de l'utilisateur qui effectue l'action
            $table->string('action'); // Description de l'action effectuée
            $table->text('details')->nullable(); // Détails supplémentaires sur l'action
            $table->string('ip_address')->nullable(); // Adresse IP de l'utilisateur
            $table->string('user_agent')->nullable(); // User-Agent (navigateur, appareil, etc.)
            $table->timestamps(); // Date et heure de l'action
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Lien avec la table des utilisateurs
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_actions');
    }
}
