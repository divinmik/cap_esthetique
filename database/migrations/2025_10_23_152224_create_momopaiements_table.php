<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('momopaiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facture_paiement_id')->nullable();
            $table->string("nature")->nullable();
            $table->string("inscription_id")->nullable();
            $table->string("error")->nullable();
            $table->string("message")->nullable();
            $table->string("status")->nullable();
            $table->string("transaction_id")->nullable();
            $table->string("external_ref")->nullable();
            $table->string("payment_url")->nullable();
            $table->string("operator")->nullable();
            $table->string("payer_phone")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momopaiements');
    }
};
