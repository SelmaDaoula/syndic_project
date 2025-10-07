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
        Schema::create('abonnements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('type_abonnement')->nullable();
            $table->decimal('montant', 10, 0)->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->unsignedInteger('immeuble_id')->nullable();
            $table->unsignedBigInteger('promoteur_id')->nullable()->index('fk_abon_prom');
            $table->timestamps();
            $table->enum('statut', ['actif', 'expire', 'suspendu', 'en_attente'])->nullable()->default('actif');
            $table->string('payment_ref')->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->integer('nombre_immeubles_max')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonnements');
    }
};
