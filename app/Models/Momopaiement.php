<?php

namespace App\Models;

use App\Models\User;
use App\Models\Inscription;
use App\Mail\MailInfoCompte;
use App\Models\Helper_function;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Momopaiement extends Model
{
    //
    protected $fillable = [
        "invoice_id",
        "inscription_id",
        "nature",
        "error",
        "message",
        "status",
        "transaction_id",
        "external_ref",
        "payment_url",
        "operator",
        "payer_phone",
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public static function inscriptionConfirme($id){
        
        Inscription::where('id',$id)->update([
            'is_valide'=>1
        ]);

        $inscription = Inscription::where("id",$id)->first();

        if(empty($inscription)){
         return 'Erreur de création de compte'; 
        }

        $compte = User::where('code',$inscription->code)->first();

        if($compte){
         return 'Erreur de création de compte'; 
        }
        
        User::create([
            'code'               => $inscription->code,
            'firstname'          => $inscription->firstname,
            'lastname'           => $inscription->lastname,
            'birthdate'          => $inscription->birthdate,
            'birthplace'         => $inscription->birthplace,
            'birthplace'         => $inscription->birthplace,
            'phone'              => Helper_function::phone($inscription->phone),
            'email'              => $inscription->email,
            'city'               => $inscription->city,
            'level'              => $inscription->level,
            'contact_salon'      => $inscription->contact_salon,
            'program'            => $inscription->program,
            'message'            => $inscription->message,
            'type_inscription'   => $inscription->type_inscription,
            'type_formation'     => $inscription->type_formation,
            'profile_photo_path' => $inscription->profile_photo_path,
            'is_valide'          => 1,
            'is_actif'           => 1,
            'password'           => bcrypt('secret'.$inscription->code), // change en prod
            'statut'             => 'candidat',
            'role'             => 'candidat',
        ]);

        //pwd
        $mailData = [
            'title' => 'Création de compte crée avec succès',
            'email'=>$inscription->email,
            'fullname' => $inscription->firstname." ".$inscription->lastname,
            'pwd' => 'secret'.$inscription->code,

        ];

        if($inscription->email){
            Helper_function::send_mail(new MailInfoCompte($mailData),$inscription->email);
        }
        

        return 'secret'.$inscription->code;
    }

    
}
