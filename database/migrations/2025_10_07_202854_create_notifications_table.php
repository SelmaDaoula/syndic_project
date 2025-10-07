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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('idx_notification_user');
            $table->unsignedBigInteger('immeuble_id')->nullable()->index('idx_notification_immeuble');
            $table->string('titre');
            $table->text('message');
            $table->enum('type_notification', ['info', 'warning', 'error', 'success', 'evenement', 'coupure', 'facture', 'paiement'])->index('idx_notification_type');
            $table->enum('reference_type', ['evenement', 'aviscoupure', 'facture', 'paiement', 'depense', 'ticket'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('lue')->nullable()->default(false)->index('idx_notification_lue');
            $table->timestamp('date_lecture')->nullable();
            $table->boolean('envoyee_email')->nullable()->default(false);
            $table->boolean('envoyee_push')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
