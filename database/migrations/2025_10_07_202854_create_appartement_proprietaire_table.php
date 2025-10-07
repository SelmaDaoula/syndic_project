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
        Schema::create('appartement_proprietaire', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('appartement_id')->nullable()->index('fk_app_app');
            $table->unsignedBigInteger('proprietaire_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appartement_proprietaire');
    }
};
