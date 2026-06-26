<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementCmdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiement_cmds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_commande")->index();
            $table->string("numero_commande")->nullable();
            $table->decimal("montant", 13, 2);
            $table->date('date_paiement');
            $table->text('commentaire')->nullable();
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
        Schema::dropIfExists('paiement_cmds');
    }
}
