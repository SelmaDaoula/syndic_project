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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign(['user_id'], 'fk_notification_user')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['immeuble_id'], 'notifications_ibfk_2')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['immeuble_id'], 'fk_notification_immeuble')->references(['id'])->on('immeubles')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'notifications_ibfk_1')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('fk_notification_user');
            $table->dropForeign('notifications_ibfk_2');
            $table->dropForeign('fk_notification_immeuble');
            $table->dropForeign('notifications_ibfk_1');
        });
    }
};
