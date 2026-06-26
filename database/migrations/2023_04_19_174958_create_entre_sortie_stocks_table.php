<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntreSortieStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entre_sortie_stocks', function (Blueprint $table) {
            $table->id();
            $table->string("id_prod")->nullable();
            $table->string("produit")->nullable();
            $table->string("user_name")->nullable();
            $table->string("operation")->nullable();
            $table->string("service")->nullable();
            $table->string("qte_en_stock")->nullable();
            $table->string("qte")->nullable();
            $table->string("num_charge")->nullable();
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
        Schema::dropIfExists('entre_sortie_stocks');
    }
}