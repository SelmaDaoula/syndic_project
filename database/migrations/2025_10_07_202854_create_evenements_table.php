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
        Schema::create('evenements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('immeuble_id')->index('fk_evenement_immeuble');
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->enum('type_evenement', ['assemblee_generale', 'reunion_syndic', 'maintenance', 'travaux', 'autre']);
            $table->unsignedBigInteger('bloc_id')->nullable()->index('fk_evenement_bloc');
            $table->string('lieu')->nullable();
            $table->integer('statut')->nullable();
            $table->unsignedBigInteger('created_by')->index('fk_evenement_user');
            $table->integer('participants_required')->nullable();
            $table->integer('max_participants')->nullable();
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
        Schema::dropIfExists('evenements');
    }
};
