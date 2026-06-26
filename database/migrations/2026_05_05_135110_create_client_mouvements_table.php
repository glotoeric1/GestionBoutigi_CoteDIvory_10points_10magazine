<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMouvementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('num_mouvement')->unique();

            $table->unsignedBigInteger('client_id')->nullable();

            // TYPE OF TRANSACTION
            $table->enum('type_mouvement', [
                'depot',            // client deposits money
                'achat_cash',       // paid fully in cash
                'achat_credit',     // bought on credit
                'paiement'         // repayment of debt
            ]);

            // AMOUNTS
            $table->decimal('total', 12, 2);             // total purchase or operation
            $table->decimal('montant_payer', 12, 2)->default(0); // cash paid now
            $table->decimal('montant_credit', 12, 2)->default(0); // credit used
            $table->decimal('montant_restant', 12, 2)->default(0); // unpaid part

            // OPTIONAL LINK TO INVOICE
            $table->unsignedBigInteger('invoice_id')->nullable();

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
        Schema::dropIfExists('client_mouvements');
    }
}