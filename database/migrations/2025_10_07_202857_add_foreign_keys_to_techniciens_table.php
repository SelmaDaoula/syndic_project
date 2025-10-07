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
        Schema::table('techniciens', function (Blueprint $table) {
            $table->foreign(['immeuble_id'], 'techniciens_ibfk_2')->references(['id'])->on('immeubles')->onDelete('SET NULL');
            $table->foreign(['user_id'], 'techniciens_ibfk_1')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('techniciens', function (Blueprint $table) {
            $table->dropForeign('techniciens_ibfk_2');
            $table->dropForeign('techniciens_ibfk_1');
        });
    }
};
