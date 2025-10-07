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
        Schema::create('paiements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference', 100);
            $table->unsignedBigInteger('facture_id')->index('fk_paiement_facture');
            $table->unsignedBigInteger('appartement_id')->index('fk_paiement_appartement');
            $table->unsignedBigInteger('proprietaire_id')->index('fk_paiement_proprietaire');
            $table->decimal('montant', 10, 0)->nullable();
            $table->bigInteger('mode_paiement');
            $table->string('reference_transaction')->nullable();
            $table->date('date_paiement');
            $table->integer('statut')->nullable();
            $table->unsignedBigInteger('created_by')->index('fk_paiement_createur');
            $table->unsignedBigInteger('validee_par')->nullable()->index('fk_paiement_valideur');
            $table->text('notes')->nullable();
            $table->string('recu_path', 500)->nullable();
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
        Schema::dropIfExists('paiements');
    }
};
