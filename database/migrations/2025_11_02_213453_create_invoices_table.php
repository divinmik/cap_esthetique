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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users'); //candidat
            $table->string('nature');
            $table->string('reference');
            $table->int('year');
            $table->date('submitted_at');
            $table->string('submitted_at');
            $table->string('month');
            $table->string('number')->unique();
            $table->string('title')->nullable(); // ex: "Frais d'inscription"
            $table->unsignedInteger('amount'); // en FCFA (ou centimes selon ton choix)
            $table->unsignedInteger('amount_paid'); // en FCFA (ou centimes selon ton choix)
            $table->enum('status',['unpaid','partial','paid','cancelled'])->default('unpaid')->index();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->json('meta')->nullable(); // détail enfants/ligne, etc.
            $table->timestamps();
        });

        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); //candidat
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type',['registration','invoice']);
            $table->enum('channel',['sms','email','both'])->default('sms');
            $table->string('to_contact')->nullable(); // téléphone ou email utilisé
            $table->text('message')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('payment_reminders');
        Schema::dropIfExists('invoices');
    }
};
