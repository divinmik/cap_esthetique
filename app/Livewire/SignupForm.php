<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inscription;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\Helper_function;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SweetAlert2\Laravel\Traits\WithSweetAlert;


class SignupForm extends Component
{
    use WithFileUploads;
    use WithSweetAlert;

    public $firstname;
    public $lastname;
    public $birthdate;
    public $birthplace;
    public $address;
    public $phone;
    public $email;
    public $city;
    public $level;
    public $level_other;
    public $contact_salon;
    public $program;
    public $message;
    public $profile_photo_path;
    public $currentUrl;
    public $type_inscription;
    public $type_formation;
    public $formations = ['Formation présentielle – Pointe-Noire
','Formation présentielle – Brazzaville
','Formation à distance (classe virtuelle)','Autres villes'];
    public $consent = false;

    public $signature_file;       // for file upload (Livewire)
    public $signature_data;       // base64 string (canvas)

    public $saved = false;
    public $savedPath = null;

    protected $listeners = ['signatureSaved' => 'onSignatureSaved'];

  /*   protected $rule = [
        'firstname' => 'required|string|max:100',
        'lastname'  => 'required|string|max:100',
        'birthdate' => 'nullable|date',
        'birthplace'=> 'nullable|string|max:150',
        'address'   => 'nullable|string|max:255',
        'phone'     => 'required|string|max:50',
        'email'     => 'required|email|max:150',
        'city'      => 'required|string',
        'level'     => ['required', Rule::in(['CEP','BEPC','Autre'])],
        'level_other' => 'nullable|string|max:100',
        'contact_salon' => ['required', Rule::in(['Oui','Non'])],
        'program' => 'nullable|string|max:100',
        'message' => 'nullable|string|max:1000',
        'consent' => 'accepted',
        // signature_file validated conditionally in submit()
    ]; */

    public function mount(){
        $this->currentUrl = url()->current();
    }
    
    public function resets(){
        $this->firstname='';
        $this->lastname='';
        $this->birthdate='';
        $this->birthplace='';
        $this->address='';
        $this->phone='';
        $this->email='';
        $this->city='';
        $this->level='';
        $this->level_other='';
        $this->contact_salon='';
        $this->program='';
        $this->message='';
        $this->profile_photo_path='';
        $this->currentUrl='';
        $this->type_inscription='';
        $this->type_formation='';
        $this->consent='';
    }

    public function onSignatureSaved($dataUrl)
    {
        // Received base64 data from JS canvas
        $this->signature_data = $dataUrl;
        
        $this->dispatchBrowserEvent('signature-saved-feedback', ['msg' => 'Signature reçue (canvas).']);
    }

    public function submit()
    {
        $this->validate([
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'type_formation'  => 'required|string|max:100',
            'type_inscription'  => 'nullable|string|max:100',
            'birthdate' => 'required|date',
            'contact_salon' => 'required',
            'birthplace'=> 'required|string|max:150',
            'address'   => 'required|string|max:255',
            'phone'     => ['required','phone:CG',function($attribute, $value, $fail){
                $tel = Helper_function::phone($value);
                $result = Inscription::where('phone',$tel)->first();
                if (!empty($result)) {
                  return $fail(__('Change le numéro de téléphone'));
                }},'unique:inscriptions'],
            'email'     => 'nullable|email|max:150',
            'city'      => 'required|string',
            'level'     => ['required', Rule::in(['CEP','BEPC','BAC','Autre'])],
            'level_other' => 'nullable|string|max:100',
            'program' => 'nullable|string|max:100',
            'consent' => 'accepted',
            'profile_photo_path' => 'required|image|max:5120',
            
            // signature_file validated conditionally in submit()
        ]);

        // If user uploaded a signature_file (Livewire temporary file)
        $signaturePath = null;

        if ($this->profile_photo_path) {
            $name = 'profil_'.Str::random(12).'.'.$this->profile_photo_path->getClientOriginalExtension();
            $signaturePath = $this->profile_photo_path->storeAs('profils', $name, 'public');;
        } 
        
        
        $code = Helper_function::UniqueInscription();

        $payload = [
            'code' => $code,
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
            'type_formation' => $this->type_formation,
            'profile_photo_path' => $signaturePath,
            'date'=>date("d/m/Y"),
        ];

        //save data

        Inscription::create($payload);

        //SEND SMS

        //genere pdf 
    
        $path = null;
        $signature = null;

            $templateFile = public_path('file') . '/attestation_inscription.docx';
            $logo = public_path('assets/images/logo.png');
            $profile_photo_path = public_path('storage/'.$signaturePath);
            $path = Helper_function::qrSvg($code);
            
            $save_ex = PDF::loadView('pdf.file_pdf', compact('payload','path','profile_photo_path','logo','signature'))->save(public_path('att_pdf/'.$code.'_'.date('d_m_y').'.pdf'));

            $attestation = public_path('att_pdf/'.$code.'_'.date('d_m_y').'.pdf');

            //condition si l'attestation a été crée
            if(file_exists($attestation) != true){
                alert()->error('403','Si le problème perciste, rapproché vous de la DEC ');
                return redirect()->to($this->currentUrl);
            }

            $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Transfer-Encoding'=>'binary',
            'Cache-Control' => 'max-age=0'
        ];
        
        // Afficher un toast côté front (Livewire ou session flash)
        $this->resets();
        $this->swalSuccess([
            'title' => 'Inscription réussie',
        ]);
       
        // Télécharger le fichier et le supprimer après envoi
        return response()->download($attestation)->deleteFileAfterSend(true);
        
    }

    public function render()
    {
        return view('livewire.signup-form');
    }
}
