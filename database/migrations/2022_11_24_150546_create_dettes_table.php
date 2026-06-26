<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dettes', function (Blueprint $table) {
            $table->id();
            $table->string("clientId")->nullable();
            $table->string("code_barre")->nullable();
            $table->string("id_prod")->nullable();
            $table->string("nom_produit")->nullable();
            $table->decimal("prix", 13, 2)->nullable();
            $table->float("quantite")->nullable();
            $table->decimal("montant", 13, 2)->nullable();

            $table->string("nom")->nullable();
            $table->string("contact")->nullable();

            $table->decimal("montantDonner", 13, 2)->nullable();
            $table->decimal("restant", 13, 2)->nullable();
            $table->string("comments")->nullable();

            $table->string("username")->nullable();
            $table->string("categorie")->nullable();

            $table->float("tva")->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("total_tva", 13, 2)->nullable();
            $table->decimal("total_ttc", 13, 2)->nullable();

            $table->text("cart")->nullable();
            $table->date("dateApayer")->nullable();
            $table->date("datepayer")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_boutique')->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();

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
        Schema::dropIfExists('dettes');
    }
}