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
        Schema::table('factures', function (Blueprint $table) {
            $table->foreign(['proprietaire_id'], 'factures_ibfk_2')->references(['id'])->on('proprietaires')->onDelete('CASCADE');
            $table->foreign(['appartement_id'], 'factures_ibfk_1')->references(['id'])->on('appartements')->onDelete('CASCADE');
            $table->foreign(['created_by'], 'factures_ibfk_3')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign('factures_ibfk_2');
            $table->dropForeign('factures_ibfk_1');
            $table->dropForeign('factures_ibfk_3');
        });
    }
};
