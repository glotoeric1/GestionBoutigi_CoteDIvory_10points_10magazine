<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoutiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boutiques', function (Blueprint $table) {
            $table->id();
            $table->string("gerant_boutique")->nullable();
            $table->string("nom_boutique")->nullable();
            $table->string("adresse")->nullable();
            $table->string("contact")->nullable();
            $table->string("contact_gerant")->nullable();
            $table->string("type")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->string("logo")->nullable();
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
        Schema::dropIfExists('boutiques');
    }
}