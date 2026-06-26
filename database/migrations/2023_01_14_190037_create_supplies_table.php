<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string("numero_commande");
            $table->unsignedBigInteger("id_fournisseur")->nullable();
            $table->string("operation")->nullable();

            $table->decimal("fraisLogistique", 13, 2)->nullable();
            $table->decimal("fraisTransit", 13, 2)->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("montantDonner", 13, 2)->nullable();
            $table->decimal("restant", 13, 2)->nullable();
            
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger("id_user")->nullable();
            $table->string("statut")->default("en cours");
            $table->date("dates")->nullable();
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
        Schema::dropIfExists('supplies');
    }
}