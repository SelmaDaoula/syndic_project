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
        Schema::create('evenement_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('evenement_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->enum('statut_participation', ['invite', 'confirme', 'absent', 'present'])->nullable()->default('invite');
            $table->timestamp('date_reponse')->nullable();
            $table->timestamps();

            $table->index(['evenement_id', 'user_id'], 'idx_evenement_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evenement_participants');
    }
};
