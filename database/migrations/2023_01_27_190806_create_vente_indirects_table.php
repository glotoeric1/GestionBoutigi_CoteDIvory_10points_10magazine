<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenteIndirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_indirects', function (Blueprint $table) {
            $table->id();
            $table->string("clientId")->nullable();
            $table->string("nom")->nullable();
            $table->string("contact")->nullable();
            $table->string("produit")->nullable();
            $table->string("descs")->nullable();
            $table->decimal("prix_init", 13, 2)->nullable();
            $table->float("qte")->nullable();
            $table->decimal("montant", 13, 2)->nullable();

            $table->decimal("montantPay", 13, 2)->nullable();
            $table->string("done_by")->nullable();

            $table->float("tva")->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("total_tva", 13, 2)->nullable();
            $table->decimal("total_ttc", 13, 2)->nullable();
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
        Schema::dropIfExists('vente_indirects');
    }
}