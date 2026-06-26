<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("app_name")->nullable();
            $table->string("logo")->nullable();
            $table->string("logo2")->nullable();
            $table->string("types")->nullable();
            $table->string("title")->nullable();
            $table->string("address")->nullable();
            $table->string("contact")->nullable();
            $table->string("footer")->nullable();
            $table->string("name_user")->nullable();
            $table->string("contact_user")->nullable();
            $table->string("sms")->default('NON');
            $table->string("senderName")->nullable();
            $table->string("email")->nullable();
            $table->string("password")->nullable();
            $table->string("msgAchat")->nullable();
            $table->string("msgAnnuler")->nullable();
            $table->string("sidebar")->nullable();
            $table->string("navbar")->nullable();
            $table->string("login")->nullable();
            $table->string("warning_message")->nullable();
            $table->string("code")->nullable();
            $table->string("app_statut")->default('OUI');
            $table->string("qte_alert")->nullable();
            $table->string("bar_option")->nullable();
            $table->date("date_fin")->nullable();
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
        Schema::dropIfExists('settings');
    }
}