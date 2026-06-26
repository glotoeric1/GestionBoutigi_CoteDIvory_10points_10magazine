<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('num_vente');
            $table->unsignedBigInteger("client_id")->nullable();
            
            $table->float("tva")->nullable();
            $table->decimal("montantDonner", 13, 2)->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("total_tva", 13, 2)->nullable();
            $table->decimal("total_ttc", 13, 2)->nullable();

            $table->decimal("reduction", 13, 2)->nullable();
            $table->decimal("restant", 13, 2)->nullable();
            
            $table->unsignedBigInteger("username")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_boutique')->nullable();
            
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
        Schema::dropIfExists('ventes');
    }
}