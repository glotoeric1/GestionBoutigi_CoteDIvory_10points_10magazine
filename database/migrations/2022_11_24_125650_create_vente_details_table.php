<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vente_id');
            $table->string("code_barre")->nullable();
            $table->string("id_prod")->nullable();
            $table->string("options")->nullable();
            $table->decimal("prix", 13, 2)->nullable();
            $table->float("quantite")->nullable();
            $table->decimal("montant", 13, 2)->nullable();
            $table->string('valider')->default('Non valider');
            $table->unsignedBigInteger("categorie")->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();

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
        Schema::dropIfExists('vente_details');
    }
}