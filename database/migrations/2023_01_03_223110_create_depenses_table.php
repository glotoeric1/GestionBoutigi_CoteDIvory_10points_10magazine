<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->string("id_user");
            $table->string("titre");
            $table->string("numero")->nullable();
            $table->text("descs")->nullable();
            $table->decimal("montant", 13, 2);
            $table->date("dates");
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
        Schema::dropIfExists('depenses');
    }
}