<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_transfers', function (Blueprint $table) {
            $table->id();
            $table->string("id_prod")->nullable();
            $table->integer("quantite")->nullable();
            $table->integer('qte');
            $table->decimal("prix_achat", 13, 2)->nullable();

            $table->string("username")->nullable();
            $table->unsignedBigInteger('id_setting')->nullable();
            $table->unsignedBigInteger('id_boutique')->nullable();

            $table->enum("statut", ["Completed", "Cancelled"])->default("Completed");

            //Add foreign key to "entrepot_id"
            $table->foreignId('entrepot_id')
                ->constrained('entrepots')
                ->cascadeOnDelete();
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
        Schema::dropIfExists('history_transfers');
    }
}