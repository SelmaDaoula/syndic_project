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
        Schema::create('rapports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom_rapport');
            $table->unsignedBigInteger('immeuble_id')->index('idx_rapport_immeuble');
            $table->enum('type_rapport', ['financier', 'maintenance', 'occupation', 'performance', 'incidents', 'personnalise'])->index('idx_rapport_type');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->json('donnees_rapport');
            $table->unsignedBigInteger('genere_par')->index('genere_par');
            $table->enum('statut', ['en_cours', 'complete', 'erreur'])->nullable()->default('en_cours');
            $table->string('fichier_path', 500)->nullable();
            $table->decimal('total_depenses', 12)->nullable()->default(0);
            $table->decimal('total_recettes', 12)->nullable()->default(0);
            $table->integer('nombre_factures')->nullable()->default(0);
            $table->integer('nombre_incidents')->nullable()->default(0);
            $table->decimal('taux_occupation', 5)->nullable()->default(0);
            $table->timestamps();

            $table->index(['periode_debut', 'periode_fin'], 'idx_rapport_periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rapports');
    }
};
