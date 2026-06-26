<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string("code_barre")->nullable();
            $table->string("id_prod")->nullable();
            $table->string("id_categorie")->nullable();
            $table->integer("quantite")->nullable();
            $table->decimal("prix_achat", 13, 2)->nullable();
            $table->decimal("Total_achat", 13, 2)->nullable();
            $table->decimal("Total_en_gros", 13, 2)->nullable();
            $table->decimal("Total_benefice_en_gros", 13, 2)->nullable();
            $table->decimal("Total_en_detail", 13, 2)->nullable();
            $table->decimal("Total_benefice_en_detail", 13, 2)->nullable();
            $table->string("options_barcode");
            $table->integer("qte_par_carton")->nullable();
            $table->integer("qte_total_en_detail")->nullable();
            //New file
            $table->decimal("prix_vente_en_gros", 13, 2)->nullable();
            $table->decimal("prix_vente_unitaire", 13, 2)->nullable();
            $table->string("username")->nullable();
            $table->string("id_fournisseur")->nullable();
            $table->date("date_expiration")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();

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
        Schema::dropIfExists('produits');
    }
}