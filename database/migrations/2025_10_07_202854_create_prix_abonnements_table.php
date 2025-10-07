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
        Schema::create('prix_abonnements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_abonnement', 50)->unique('type_abonnement_unique');
            $table->string('nom', 100);
            $table->decimal('prix', 10);
            $table->integer('duree_mois');
            $table->integer('max_immeubles')->default(1);
            $table->boolean('is_active')->default(true)->index('is_active_index');
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
        Schema::dropIfExists('prix_abonnements');
    }
};
