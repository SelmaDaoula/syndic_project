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
        Schema::table('ticketincidents', function (Blueprint $table) {
            $table->foreign(['appartement_id'], 'ticketincidents_ibfk_2')->references(['id'])->on('appartements')->onDelete('SET NULL');
            $table->foreign(['created_by'], 'ticketincidents_ibfk_4')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['immeuble_id'], 'ticketincidents_ibfk_1')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['bloc_id'], 'ticketincidents_ibfk_3')->references(['id'])->on('blocs')->onDelete('SET NULL');
            $table->foreign(['assignee_id'], 'ticketincidents_ibfk_5')->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketincidents', function (Blueprint $table) {
            $table->dropForeign('ticketincidents_ibfk_2');
            $table->dropForeign('ticketincidents_ibfk_4');
            $table->dropForeign('ticketincidents_ibfk_1');
            $table->dropForeign('ticketincidents_ibfk_3');
            $table->dropForeign('ticketincidents_ibfk_5');
        });
    }
};
