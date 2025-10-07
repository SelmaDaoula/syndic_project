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
        Schema::create('aviscoupures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('immeuble_id')->index('fk_coupure_immeuble');
            $table->enum('type_coupure', ['eau', 'electricite', 'gaz', 'internet', 'ascenseur', 'maintenance']);
            $table->string('description')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->string('titre')->nullable();
            $table->unsignedBigInteger('bloc_id')->nullable()->index('fk_coupure_bloc');
            $table->dateTime('duree_estimee')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'reporte'])->nullable()->default('planifie');
            $table->enum('urgence', ['faible', 'normale', 'haute', 'critique'])->nullable()->default('normale');
            $table->unsignedBigInteger('created_by')->index('fk_coupure_user');
            $table->string('entreprise_responsable')->nullable();
            $table->string('contact_urgence')->nullable();
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
        Schema::dropIfExists('aviscoupures');
    }
};
