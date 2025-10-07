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
        Schema::create('techniciens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('telephone', 20);
            $table->unsignedBigInteger('user_id')->unique('user_id');
            $table->json('specialites');
            $table->unsignedBigInteger('immeuble_id')->nullable()->index('idx_technicien_immeuble');
            $table->boolean('is_external')->nullable()->default(false);
            $table->decimal('tarif_horaire')->nullable();
            $table->boolean('is_suspended')->nullable()->default(false)->index('idx_technicien_suspended');
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
        Schema::dropIfExists('techniciens');
    }
};
