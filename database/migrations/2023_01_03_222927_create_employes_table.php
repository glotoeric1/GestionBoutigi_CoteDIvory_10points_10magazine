<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->nullable();
            $table->string("contact")->nullable();
            $table->string("adresse")->nullable();
            $table->string("post")->nullable();
            $table->decimal("salaire")->nullable();
            $table->date("dateStart")->nullable();
            $table->date("dateEnd")->nullable();
            $table->string("emergency_name")->nullable();
            $table->string("relationship")->nullable();
            $table->string("contact_joint")->nullable();
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
        Schema::dropIfExists('employes');
    }
}