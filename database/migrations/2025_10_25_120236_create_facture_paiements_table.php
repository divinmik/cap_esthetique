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
        Schema::create('facture_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('nature');
            $table->string('mois');
            $table->string('number')->unique();
            $table->string('title')->nullable(); // ex: "Frais d'inscription"
            $table->unsignedInteger('amount'); // en FCFA (ou centimes selon ton choix)
            $table->enum('status',['unpaid','partial','paid','cancelled'])->default('unpaid')->index();
            $table->date('due_date')->nullable();
            $table->json('meta')->nullable(); // détail enfants/ligne, etc.
            $table->timestamps();
        });

        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type',['registration','invoice']);
            $table->enum('channel',['sms','email','both'])->default('sms');
            $table->string('to_contact'); // téléphone ou email utilisé
            $table->text('message');
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_paiements');
    }
};
