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
        Schema::table('aviscoupures', function (Blueprint $table) {
            $table->foreign(['immeuble_id'], 'fk_coupure_immeuble')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['bloc_id'], 'fk_coupure_bloc')->references(['id'])->on('blocs')->onDelete('SET NULL');
            $table->foreign(['created_by'], 'fk_coupure_user')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aviscoupures', function (Blueprint $table) {
            $table->dropForeign('fk_coupure_immeuble');
            $table->dropForeign('fk_coupure_bloc');
            $table->dropForeign('fk_coupure_user');
        });
    }
};
