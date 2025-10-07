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
        Schema::create('factures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_facture', 100)->unique('numero_facture');
            $table->unsignedBigInteger('appartement_id')->index('idx_facture_appartement');
            $table->unsignedBigInteger('proprietaire_id')->index('idx_facture_proprietaire');
            $table->integer('periode_mois');
            $table->integer('periode_annee');
            $table->decimal('charges_communes', 10)->nullable()->default(0);
            $table->decimal('charges_eau', 10)->nullable()->default(0);
            $table->decimal('charges_electricite', 10)->nullable()->default(0);
            $table->decimal('charges_maintenance', 10)->nullable()->default(0);
            $table->decimal('charges_securite', 10)->nullable()->default(0);
            $table->decimal('charges_nettoyage', 10)->nullable()->default(0);
            $table->decimal('autres_charges', 10)->nullable()->default(0);
            $table->decimal('montant_total', 12);
            $table->decimal('montant_paye', 12)->nullable()->default(0);
            $table->decimal('montant_restant', 12)->nullable()->storedAs('`montant_total` - `montant_paye`');
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            $table->enum('statut', ['emise', 'envoyee', 'payee', 'en_retard', 'annulee'])->nullable()->default('emise')->index('idx_facture_statut');
            $table->unsignedBigInteger('created_by')->index('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['periode_annee', 'periode_mois'], 'idx_facture_periode');
            $table->unique(['appartement_id', 'periode_mois', 'periode_annee'], 'unique_facture_periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factures');
    }
};
