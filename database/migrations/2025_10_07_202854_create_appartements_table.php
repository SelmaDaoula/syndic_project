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
        Schema::create('appartements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type_appartement', ['studio', 'F2', 'F3', 'F4', 'F5+']);
            $table->decimal('surface')->nullable();
            $table->unsignedBigInteger('bloc_id')->index('fk_app_bloc');
            $table->unsignedBigInteger('proprietaire_id')->nullable();
            $table->integer('nombre_pieces');
            $table->enum('statut', ['libre', 'occupe', 'maintenance'])->nullable()->default('libre');
            $table->string('numero', 50)->nullable();
            $table->timestamps();

            $table->unique(['numero', 'bloc_id'], 'unique_numero_bloc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appartements');
    }
};
