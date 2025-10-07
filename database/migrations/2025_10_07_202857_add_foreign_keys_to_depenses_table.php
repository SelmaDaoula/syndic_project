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
        Schema::table('depenses', function (Blueprint $table) {
            $table->foreign(['bloc_id'], 'fk_depense_bloc')->references(['id'])->on('blocs')->onDelete('SET NULL');
            $table->foreign(['immeuble_id'], 'fk_depense_immeuble')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['approuvee_par'], 'fk_depense_approuvee')->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['created_by'], 'fk_depense_createur')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign('fk_depense_bloc');
            $table->dropForeign('fk_depense_immeuble');
            $table->dropForeign('fk_depense_approuvee');
            $table->dropForeign('fk_depense_createur');
        });
    }
};
