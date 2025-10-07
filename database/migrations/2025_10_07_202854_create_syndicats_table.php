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
        Schema::create('syndicats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->unsignedBigInteger('user_id')->nullable()->index('fk_user_synd');
            $table->tinyInteger('is_suspended')->nullable();
            $table->timestamps();
            $table->string('prenom');
            $table->string('email');
            $table->integer('telephone')->nullable();
            $table->string('licence_numero', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('syndicats');
    }
};
