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
        Schema::create('proprietaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->integer('telephone')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('appartement_id')->nullable()->index('fk_prop_app');
            $table->unsignedBigInteger('user_id')->index('fk_prop_user');
            $table->boolean('is_suspended')->nullable()->default(false);
            $table->unsignedInteger('abonnement_id')->nullable();
            $table->integer('date_acquisition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proprietaires');
    }
};
