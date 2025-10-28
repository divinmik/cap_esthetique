<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Inscription extends Model
{
    //

    protected $fillable = [
        'code',
        'firstname',
        'lastname',
        'birthdate',
        'birthplace',
        'address',
        'phone',
        'email',
        'city',
        'level',
        'contact_salon',
        'program',
        'message',
        'type_inscription',
        'type_formation',
        'profile_photo_path',
        'is_valide',
    ];

     protected $casts = [
        'consent' => 'boolean',
        'birthdate' => 'date',
    ];

    // helper to get public url for signature if stored in storage/app/public
    public function signatureUrl()
    {
        return $this->signature_path ? Storage::url($this->signature_path) : null;
    }

    public function photoUrl()
    {  
        /*$path = $this->profile_photo_path;
        $filename = str_replace('/storage/profils/', '', $path);
        */
       // dd(file_exists(public_path('storage/'.$this->profile_photo_path)));
        return $this->profile_photo_path ? Storage::url($this->profile_photo_path) : null;
    }
}
