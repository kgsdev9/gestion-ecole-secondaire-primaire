<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFactorColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_secret')->nullable(); // Le secret pour Google Authenticator
            $table->boolean('two_factor_enabled')->default(false); // Si l'authentification à deux facteurs est activée
            $table->text('two_factor_recovery_codes')->nullable(); // Les codes de récupération pour la 2FA
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_factor_secret');
            $table->dropColumn('two_factor_enabled');
            $table->dropColumn('two_factor_recovery_codes');
        });

    }
}
