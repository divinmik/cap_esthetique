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
            $table->unsignedBigInteger('frais_accademique_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('reference');
            $table->string('nature');
            $table->string('mois');
            $table->string('statut')->default(0);
            $table->string('reste')->default(0);
            $table->string('montant');
            $table->string('is_valide')->defaut(0);
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
