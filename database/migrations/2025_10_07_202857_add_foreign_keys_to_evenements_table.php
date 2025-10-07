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
        Schema::table('evenements', function (Blueprint $table) {
            $table->foreign(['immeuble_id'], 'fk_evenement_immeuble')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['bloc_id'], 'fk_evenement_bloc')->references(['id'])->on('blocs')->onDelete('SET NULL');
            $table->foreign(['created_by'], 'fk_evenement_user')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropForeign('fk_evenement_immeuble');
            $table->dropForeign('fk_evenement_bloc');
            $table->dropForeign('fk_evenement_user');
        });
    }
};
