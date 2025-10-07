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
        Schema::table('evenement_participants', function (Blueprint $table) {
            $table->foreign(['user_id'], 'evenement_participants_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['evenement_id'], 'evenement_participants_ibfk_1')->references(['id'])->on('evenements')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evenement_participants', function (Blueprint $table) {
            $table->dropForeign('evenement_participants_ibfk_2');
            $table->dropForeign('evenement_participants_ibfk_1');
        });
    }
};
