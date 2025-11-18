<?php

namespace App\Models;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Momopaiement;
use App\Models\ModuleContent;
use App\Livewire\Sectiondash\Facture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(ModuleContent::class);
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Momopaiement::class, 'inscription_id');
    }

        public function momopaiements(): HasMany
    {
        return $this->hasMany(Momopaiement::class);
    }

 /*    public function factures(): HasMany
    {
        return $this->hasMany(Invoice::class, 'inscription_id'); // si tu lies les factures à l'inscription
    }
 */
    /** Payée s'il existe un momopaiement success */
    public function scopePayeesViaMomo($q)
    {
        return $q->whereHas('momopaiements', fn($m) => $m->where('status','SUCCESS'));
    }

    public function scopeNonPayeesViaMomo($q)
    {
        return $q->whereDoesntHave('momopaiements', fn($m) => $m->where('status','SUCCESS'));
    }


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
