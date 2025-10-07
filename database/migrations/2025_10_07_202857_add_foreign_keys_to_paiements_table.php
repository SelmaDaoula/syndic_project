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
        Schema::table('paiements', function (Blueprint $table) {
            $table->foreign(['created_by'], 'fk_paiement_createur')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['proprietaire_id'], 'fk_paiement_proprietaire')->references(['id'])->on('proprietaires')->onDelete('CASCADE');
            $table->foreign(['appartement_id'], 'fk_paiement_appartement')->references(['id'])->on('appartements')->onDelete('CASCADE');
            $table->foreign(['facture_id'], 'fk_paiement_facture')->references(['id'])->on('factures')->onDelete('CASCADE');
            $table->foreign(['validee_par'], 'fk_paiement_valideur')->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign('fk_paiement_createur');
            $table->dropForeign('fk_paiement_proprietaire');
            $table->dropForeign('fk_paiement_appartement');
            $table->dropForeign('fk_paiement_facture');
            $table->dropForeign('fk_paiement_valideur');
        });
    }
};
