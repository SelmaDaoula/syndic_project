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
        Schema::table('immeubles', function (Blueprint $table) {
            $table->foreign(['abonnement_id'], 'immeubles_ibfk_2')->references(['id'])->on('abonnements');
            $table->foreign(['promoteur_id'], 'immeubles_ibfk_1')->references(['id'])->on('promoteurs')->onDelete('CASCADE');
            $table->foreign(['syndic_id'], 'immeubles_ibfk_3')->references(['id'])->on('syndicats')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('immeubles', function (Blueprint $table) {
            $table->dropForeign('immeubles_ibfk_2');
            $table->dropForeign('immeubles_ibfk_1');
            $table->dropForeign('immeubles_ibfk_3');
        });
    }
};
