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
        /*
        'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'birthdate' => $this->birthdate,
            'birthplace'=> $this->birthplace,
            'address'   => $this->address,
            'phone'     => $this->phone,
            'email'     => $this->email,
            'city'      => $this->city,
            'level'     => $this->level === 'Autre' ? ($this->level_other ?? 'Autre') : $this->level,
            'contact_salon' => $this->contact_salon,
            'program' => $this->program,
            'message' => $this->message,
            'type_inscription' => $this->type_inscription,
            'profile_photo_path' => $signaturePath,
        */
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('birthdate');
            $table->string('birthplace');
            $table->string('address')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city');
            $table->string('level');
            $table->string('contact_salon')->nullable();
            $table->string('program')->nullable();
            $table->string('message')->nullable();
            $table->string('type_inscription')->nullable();
            $table->string('type_formation');
            $table->text('profile_photo_path');
            $table->integer('is_valide')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
