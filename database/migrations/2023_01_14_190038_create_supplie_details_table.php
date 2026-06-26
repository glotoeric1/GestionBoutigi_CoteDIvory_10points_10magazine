<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplieDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplie_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("supplie_id");
            $table->unsignedBigInteger("id_prod");
            $table->unsignedBigInteger("id_cat")->nullable();
            $table->integer("qte_commander")->nullable();
            $table->integer("qte_valider")->nullable();
            $table->decimal("prix", 13, 2)->nullable();
            $table->decimal("montant", 13, 2)->nullable();
            
            $table->decimal("prix_detail", 13, 2)->nullable();
            $table->decimal("total_detail", 13, 2)->nullable();
            $table->decimal("prix_gros", 13, 2)->nullable();
            $table->decimal("total_gros", 13, 2)->nullable();
            
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
        Schema::dropIfExists('supplie_details');
    }
}
