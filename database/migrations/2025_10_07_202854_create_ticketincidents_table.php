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
        Schema::create('ticketincidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_ticket', 100)->unique('numero_ticket');
            $table->string('titre');
            $table->text('description');
            $table->unsignedBigInteger('immeuble_id')->index('idx_ticket_immeuble');
            $table->unsignedBigInteger('appartement_id')->nullable()->index('appartement_id');
            $table->unsignedBigInteger('bloc_id')->nullable()->index('bloc_id');
            $table->unsignedBigInteger('created_by')->index('created_by');
            $table->unsignedBigInteger('assignee_id')->nullable()->index('idx_ticket_assignee');
            $table->enum('type_incident', ['plomberie', 'electricite', 'chauffage', 'climatisation', 'nettoyage', 'securite', 'ascenseur', 'ventilation', 'autre']);
            $table->enum('priorite', ['faible', 'normale', 'haute', 'urgente'])->nullable()->default('normale')->index('idx_ticket_priorite');
            $table->enum('statut', ['ouvert', 'en_cours', 'en_attente', 'resolu', 'ferme'])->nullable()->default('ouvert')->index('idx_ticket_statut');
            $table->date('date_incident');
            $table->date('date_resolution')->nullable();
            $table->decimal('cout_estime', 10)->nullable();
            $table->decimal('cout_reel', 10)->nullable();
            $table->json('photos')->nullable();
            $table->text('notes_resolution')->nullable();
            $table->tinyInteger('satisfaction_client')->nullable();
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
        Schema::dropIfExists('ticketincidents');
    }
};
