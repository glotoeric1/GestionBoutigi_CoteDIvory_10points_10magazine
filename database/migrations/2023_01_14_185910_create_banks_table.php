<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string("numero_de_compte")->nullable();
            $table->string("operation")->nullable();
            $table->decimal("montant_depot", 13, 2)->nullable();
            $table->decimal("montant_retrait", 13, 2)->nullable();
            $table->decimal("montant_remise", 13, 2)->nullable();
            $table->decimal("montant", 13, 2)->nullable();
            $table->string("done_by")->nullable();
            $table->date("dates")->nullable();
            $table->string("descs")->nullable();
            $table->string("numero")->nullable();
            $table->string("id_user")->nullable();
            $table->integer('id_setting')->nullable();
            $table->integer('id_boutique')->nullable();
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
        Schema::dropIfExists('banks');
    }
}