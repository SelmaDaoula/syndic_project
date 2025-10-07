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
        Schema::create('depenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference', 100);
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('montant', 10, 0)->nullable();
            $table->unsignedBigInteger('immeuble_id')->index('fk_depense_immeuble');
            $table->unsignedBigInteger('bloc_id')->nullable()->index('fk_depense_bloc');
            $table->enum('categorie', ['maintenance', 'reparation', 'nettoyage', 'securite', 'administratif', 'energie', 'autre']);
            $table->enum('type_depense', ['recurrente', 'ponctuelle', 'urgence'])->nullable()->default('ponctuelle');
            $table->date('date_depense');
            $table->string('fournisseur')->nullable();
            $table->string('numero_facture', 100)->nullable();
            $table->enum('statut', ['prevue', 'approuvee', 'payee', 'refusee'])->nullable()->default('prevue');
            $table->unsignedBigInteger('approuvee_par')->nullable()->index('fk_depense_approuvee');
            $table->unsignedBigInteger('created_by')->index('fk_depense_createur');
            $table->string('piece_justificative', 500)->nullable();
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
        Schema::dropIfExists('depenses');
    }
};
