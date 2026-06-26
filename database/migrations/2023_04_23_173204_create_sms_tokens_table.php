<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('access_token')->nullable();
            $table->string('expires_in')->nullable();
            $table->dateTime('expiration_time')->nullable();
            $table->integer('id_setting')->nullable();
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
        Schema::dropIfExists('sms_tokens');
    }
}