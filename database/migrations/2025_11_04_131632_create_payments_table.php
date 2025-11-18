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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Liens
            $table->foreignId('invoice_id')
                  ->constrained()
                  ->cascadeOnDelete();          // si la facture disparaît, on supprime les paiements
            $table->foreignId('user_id')
                  ->constrained()
                  ->restrictOnDelete();         // empêche de supprimer l'user s'il a des paiements

            // Montant & infos
            $table->unsignedInteger('amount'); 
            $table->string('method', 30)->default('cash'); // ex: cash|momo|card|bank
            $table->string('external_ref', 100)->nullable(); // réf PSP, transaction MoMo, etc.
            $table->string('idempotency_key', 64)->nullable()->unique(); // ex: sha256(invoice|ref|amount)
            $table->timestamp('received_at')->index(); // date encaissement

            // Divers
            $table->json('meta')->nullable(); // ex: payload API, phone, opérateur, etc.
            $table->timestamps();

            // Index/contraintes utiles
            $table->unique(['invoice_id','external_ref']); // un même ref externe ne doit pas se répéter pour une facture
            $table->index(['invoice_id','received_at']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
