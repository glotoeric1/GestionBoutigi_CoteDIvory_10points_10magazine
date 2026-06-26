<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("clientId")->nullable();
            $table->string("nom")->nullable();
            $table->string("titre")->nullable();
            $table->string("descs")->nullable();
            $table->integer("qte")->nullable();
            $table->decimal("montant", 13, 2)->nullable();
            $table->decimal("restant", 13, 2)->nullable();
            $table->decimal("reduction", 13, 2)->nullable();
            $table->decimal("montantPay", 13, 2)->nullable();
            $table->string("done_by")->nullable();

            $table->float("tva")->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("total_tva", 13, 2)->nullable();
            $table->decimal("total_ttc", 13, 2)->nullable();
            $table->integer('id_setting')->nullable();
            $table->integer('id_boutique')->nullable();
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
        Schema::dropIfExists('services');
    }
}