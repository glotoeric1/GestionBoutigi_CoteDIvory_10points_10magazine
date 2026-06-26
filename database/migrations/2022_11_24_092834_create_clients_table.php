<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // BASIC INFO
            $table->string('nom')->nullable();
            $table->string('contact')->nullable();
            $table->string('adresse')->nullable();
            $table->string('email')->nullable();
            $table->string('types')->nullable();

            // CREDIT SYSTEM
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('credit_used', 12, 2)->default(0);

            // WALLET SYSTEM (CASH DEPOSIT ACCOUNT)
            $table->decimal('wallet_balance', 12, 2)->default(0);

            // STATUS CONTROL
            $table->enum('status', ['active', 'blocked'])->default('active');

            $table->unsignedBigInteger('id_setting')->nullable();
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
        Schema::dropIfExists('clients');
    }
}