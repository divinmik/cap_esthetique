<?php

namespace App\Livewire\Sectiondash;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profdoc extends Component
{

    public $data,$show="liste_nv";
    public $password,$password_confirmation,$old_password;

    public function mount($code){
         $this->data = User::where('code',$code)->first();
    }

     public function tabs($show)
    {
        $this->show =$show;
    }

    public function render()
    {
        return view('livewire.sectiondash.profdoc');
    }

     public function save_password()
    {
        // Validation + messages FR
        $validated = $this->validate(
            [
                'old_password' => ['required'], // vérifie le mdp actuel
                'password'     => ['required', 'string', 'min:8', 'confirmed'],
            ],
            [
                'old_password.required' => "L'ancien mot de passe est obligatoire.",
                'old_password.current_password' => "L'ancien mot de passe est incorrect.",
                'password.required'     => "Le nouveau mot de passe est obligatoire.",
                'password.min'          => "Le nouveau mot de passe doit contenir au moins :min caractères.",
                'password.confirmed'    => "La confirmation du mot de passe ne correspond pas.",
            ]
        );

        if (! Hash::check($this->old_password, Auth::user()->password)) {
           
            $this->addError('old_password', "L'ancien mot de passe est incorrect.");
            return;
        }

        // Mise à jour
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Nettoyage + feedback
        $this->reset(['old_password', 'password', 'password_confirmation']);
        $this->resetValidation();

         $this->swalSuccess([
            'title' => 'Mot de passe mis à jour avec succès.',
        ]);
    }

    public function resets()
    {
        $this->reset(['old_password', 'password', 'password_confirmation']);
        $this->resetValidation();
    }
}
