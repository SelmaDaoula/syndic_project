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
        Schema::table('locataires', function (Blueprint $table) {
            $table->foreign(['proprietaire_id'], 'fk_loc_prop')->references(['id'])->on('proprietaires')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'fk_user_loc')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['appartement_id'], 'fk_loc_app')->references(['id'])->on('appartements')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'fk_loc_user')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locataires', function (Blueprint $table) {
            $table->dropForeign('fk_loc_prop');
            $table->dropForeign('fk_user_loc');
            $table->dropForeign('fk_loc_app');
            $table->dropForeign('fk_loc_user');
        });
    }
};
