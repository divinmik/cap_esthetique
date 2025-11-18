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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('birthdate');
            $table->string('birthplace');
            $table->string('address')->nullable();
            $table->string('phone');
            $table->string('email')->unique()->nullable();
            $table->string('city')->nullable();
            $table->string('level')->nullable();
            $table->string('contact_salon')->nullable();
            $table->string('program')->nullable();
            $table->string('message')->nullable();
            $table->string('type_inscription')->nullable();
            $table->string('type_formation')->nullable();
            $table->text('profile_photo_path');
            $table->integer('is_valide')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('is_actif')->default(0);
            $table->integer('user_id')->nullable();
            $table->string('role')->default('candidat');/* admin, prof, candidat */
            $table->string('statut');/* admin, prof, candidat */
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
