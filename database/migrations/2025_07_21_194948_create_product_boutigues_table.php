<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductBoutiguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_boutigues', function (Blueprint $table) {
            $table->id();
            $table->string("code_barre")->nullable();
            $table->unsignedBigInteger("id_prod")->nullable();
            $table->unsignedBigInteger("id_categorie")->nullable();
            $table->unsignedBigInteger("username")->nullable();
            $table->unsignedBigInteger("id_fournisseur")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_boutique')->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();

            $table->integer("quantite")->nullable();
            $table->decimal("prix_achat", 13, 2)->nullable();
            $table->decimal("prix_vente_en_gros", 13, 2)->nullable();
            $table->decimal("prix_vente_unitaire", 13, 2)->nullable();
            $table->date("date_expiration")->nullable();
            
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
        Schema::dropIfExists('product_boutigues');
    }
}