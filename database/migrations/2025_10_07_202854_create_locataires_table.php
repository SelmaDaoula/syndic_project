<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->integer('telephone')->nullable();
            $table->unsignedBigInteger('appartement_id')->nullable()->index('fk_loc_app');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->index('fk_loc_user');
            $table->boolean('is_suspended')->nullable()->default(false);
            $table->unsignedBigInteger('abonnement_id')->nullable();
            $table->unsignedBigInteger('proprietaire_id')->nullable()->index('fk_loc_prop');
            $table->bigInteger('date_debut_bail')->nullable();
            $table->bigInteger('date_fin_bail')->nullable();
            $table->integer('loyer_mensuel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locataires');
    }
};
