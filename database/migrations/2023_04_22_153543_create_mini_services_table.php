<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiniServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mini_services', function (Blueprint $table) {
            $table->id();
            $table->string("nom_service")->nullable();
            $table->decimal("montant", 13, 2)->nullable();
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
        Schema::dropIfExists('mini_services');
    }
}