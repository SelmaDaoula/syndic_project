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
        Schema::table('proprietaires', function (Blueprint $table) {
            $table->foreign(['user_id'], 'fk_prop_user')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['appartement_id'], 'fk_prop_app')->references(['id'])->on('appartements')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proprietaires', function (Blueprint $table) {
            $table->dropForeign('fk_prop_user');
            $table->dropForeign('fk_prop_app');
        });
    }
};
