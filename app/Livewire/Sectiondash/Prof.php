<?php

namespace App\Livewire\Sectiondash;

use App\Models\User;
use Livewire\Component;
use App\Mail\MailInfoCompte;
use App\Models\Helper_function;
use Livewire\Componentfunction;
use Illuminate\Support\Strfunction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Prof extends Component
{

    use WithFileUploads;

    public $datas,$show,$action,
       $currentUrl
    ;
    

    public $firstname,
        $user,
        $edit_prof,
        $lastname,
        $birthdate,
        $birthplace,
        $address,
        $phone,
        $email,
        $city,
        $level,
        $contact_salon,
        $program,
        $message,
        $is_actif,
        $type_inscription,
        $type_formation,
        $profile_photo_url,
        $profile_photo_path;

    public function add()
    {
        array_push($this->input_niveau, [
            'id' => '',
            'uid' => (string) Str::uuid(), // ou uniqid()
            'ecole_id' => '',
            'libelle' => '',
            'montant' => '',
        ]);

    }
    
    public function remove($i)
    {
        unset($this->input_niveau[$i]);
        $this->input_niveau = array_values($this->input_niveau); // réindexe de 0..n-1
    }

    public function updating($k,$v)
    {
          
        if($k=="edit_prof"){
            $this->user = User::where('code',$v)->first();
            
            if($this->user){
                $this->firstname=$this->user->firstname;
                $this->lastname=$this->user->lastname;
                $this->birthdate=$this->user->birthdate;
                $this->birthplace=$this->user->birthplace;
                $this->address=$this->user->address;
                $this->phone=$this->user->phone;
                $this->email=$this->user->email;
                $this->city=$this->user->city;
                $this->level=$this->user->level;
                $this->contact_salon=$this->user->contact_salon;
                $this->program=$this->user->program;
                $this->message=$this->user->message;
                $this->type_inscription=$this->user->type_inscription;
                $this->type_formation=$this->user->type_formation;
                $this->is_actif=$this->user->is_actif;
                $this->profile_photo_url=Storage::disk('public')->url($this->user->profile_photo_path);

            }else{
                $this->reset([
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
                    'profile_photo_path']);
            }
        }
        
    }
    
    public function resets()
    {
        $this->id = "";
        $this->libelle = "";
        
    }

    public function mount(){
        $this->currentUrl = url()->current();
        $this->datas = User::where('statut','professeur')->get();
    }
    
    public function validation(){

            return $this->validate([
            'action' => 'nullable|string|max:100',
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'address'   => 'required|string|max:255',
            'birthdate'   => 'required|date',
            'birthplace'   => 'required',
            'phone'     => ['required','phone:CG',function($attribute, $value, $fail){
                $tel = Helper_function::phone($value);
                $result = User::where('phone',$tel)->first();
                if (!empty($result)) {
                  return $fail(__('Change le numéro de téléphone'));
                }},'unique:users'],
            'email'     => 'required|email|max:150|unique:users',
            'profile_photo_path' => 'required_if:action,save|image|max:5120',
            ]);
       
    }
    public function validationedit(){

            return $this->validate([
            'is_actif' => 'required|string|max:100',
            'action' => 'nullable|string|max:100',
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'address'   => 'required|string|max:255',
            'birthdate'   => 'required|date',
            'birthplace'   => 'required',
            'phone'     => ['required','phone:CG',function($attribute, $value, $fail){
                $tel = Helper_function::phone($value);
                $result = User::where('phone',$tel)
                ->where('id','!=',$this->user->id)
                ->first();
                if (!empty($result)) {
                  return $fail(__('Change le numéro de téléphone'));
                }}],
            'email'     => 'required|email|max:150|unique:users,email,'.$this->user->id,
            'profile_photo_path' => 'required_if:action,save|nullable|image|max:5120',
            ]);
       
    }


    public function render()
    {
        return view('livewire.sectiondash.prof');
    }

    public function save()
    {
        $donnes = $this->validation();
               
        $reslt  = User::SaveData($donnes,"professeur");

        toast($reslt['message'],'success');
        
        //pwd
        $mailData = [
            'title' => 'Création de compte crée avec succès',
            'email'=>$reslt['data']['email'],
            'fullname' => $reslt['data']['firstname']." ".$reslt['data']['lastname'],
            'pwd' => $reslt["password"],

        ];

        Helper_function::send_mail(new MailInfoCompte($mailData),$reslt['data']['email']);
        
        return redirect()->to($this->currentUrl);
    }
    
    public function edit()
    {
        $donnes = $this->validationedit();
        unset($donnes['action']);
        $reslt  = User::EditData($this->user->id,$donnes,$this->user);

        toast($reslt['message'],'success');
        
        //pwd
       /*  $mailData = [
            'title' => 'Mis à jours du compte créalisé avec succès',
            'email'=>$reslt['data']['email'],
            'fullname' => $reslt['data']['firstname']." ".$reslt['data']['lastname'],
            'pwd' => $reslt["password"],

        ];

        Helper_function::send_mail(new MailInfoCompte($mailData),$reslt['data']['email']);
         */
        return redirect()->to($this->currentUrl);
    }

    

}
