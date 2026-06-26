<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementAvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiement_avances', function (Blueprint $table) {
            $table->id();
            $table->string("clientId");
            $table->string("nom");
            $table->string("contact");
            $table->string("titre");
            $table->string("descs")->nullable();
            $table->string("qte");
            $table->decimal("montant", 13, 2);
            $table->decimal("total", 13, 2);
            $table->decimal("restant", 13, 2);
            $table->decimal("montantPay", 13, 2);
            $table->string("done_by");

            $table->float("tva")->nullable();
            $table->decimal("total_ht", 13, 2)->nullable();
            $table->decimal("total_tva", 13, 2)->nullable();
            $table->decimal("total_ttc", 13, 2)->nullable();

            $table->timestamps();
            $table->string("id_prod")->nullable();
            $table->string("username")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_boutique')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paiement_avances');
    }
}