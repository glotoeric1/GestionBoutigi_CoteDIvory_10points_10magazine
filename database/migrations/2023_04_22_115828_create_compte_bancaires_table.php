<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompteBancairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compte_bancaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('bank');
            $table->string('type');
            $table->string('titulaire');
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
        Schema::dropIfExists('compte_bancaires');
    }
}