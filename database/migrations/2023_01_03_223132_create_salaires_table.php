<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaires', function (Blueprint $table) {
            $table->id();
            $table->string("emp_id");
            $table->string("pay_number");
            $table->decimal("salaire");
            $table->decimal("montantRecu");
            $table->decimal("montantRestant");
            $table->integer("bonus")->nullable();
            $table->string("mois");
            $table->string("years");
            $table->string("done_by");
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
        Schema::dropIfExists('salaires');
    }
}