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
        Schema::table('rapports', function (Blueprint $table) {
            $table->foreign(['genere_par'], 'rapports_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['immeuble_id'], 'rapports_ibfk_1')->references(['id'])->on('immeubles')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropForeign('rapports_ibfk_2');
            $table->dropForeign('rapports_ibfk_1');
        });
    }
};
