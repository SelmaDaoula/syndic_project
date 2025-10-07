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
        Schema::create('immeubles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->text('adresse');
            $table->decimal('surface_totale', 10)->nullable();
            $table->integer('nombre_blocs')->nullable()->default(1);
            $table->year('annee_construction')->nullable();
            $table->enum('statut', ['construction', 'actif', 'maintenance'])->nullable()->default('actif');
            $table->unsignedBigInteger('promoteur_id')->index('idx_immeuble_promoteur');
            $table->unsignedBigInteger('abonnement_id')->nullable()->index('idx_immeuble_abonnement');
            $table->unsignedBigInteger('syndic_id')->nullable()->index('syndic_id');
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
        Schema::dropIfExists('immeubles');
    }
};
