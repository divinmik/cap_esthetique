<?php
namespace App\Http\Livewire\Candidates;

use App\Models\Invoice;
use Livewire\Component;
use App\Models\Candidate;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonnelFile extends Component
{
    use WithFileUploads;

    public Candidate $candidate;

    public string $tab = 'invoices';

    // -- Factures
    public string $invoiceFilter = 'pending'; // all | pending | paid
    public $invoices;

    // -- Modules & Quiz
    public $modules;         // modules inscrits par le candidat
    public $pendingQuizzes;  // quiz non complétés

    // -- Profil
    public $firstname, $lastname, $email, $phone, $birthdate, $birthplace;
    public $profile_photo;

    // -- Password
    public $old_password, $password, $password_confirmation;

    protected $listeners = [
        'refreshInvoices' => '$refresh',
    ];

    public function mount(Candidate $candidate)
    {
        $this->candidate = $candidate;

        // Pré-remplir profil
        $this->firstname  = $candidate->firstname;
        $this->lastname   = $candidate->lastname;
        $this->email      = $candidate->email;
        $this->phone      = $candidate->phone;
        $this->birthdate  = optional($candidate->birthdate)?->format('Y-m-d');
        $this->birthplace = $candidate->birthplace;

        $this->loadData();
    }

    public function loadData(): void
    {
        // Factures selon filtre
        $this->invoices = $this->candidate->invoices()
            ->when($this->invoiceFilter === 'pending', fn($q) => $q->where('status', 'pending'))
            ->when($this->invoiceFilter === 'paid', fn($q) => $q->where('status', 'paid'))
            ->orderByDesc('created_at')
            ->get();

        // Modules inscrits
        $this->modules = $this->candidate->modules()
            ->withCount('contents')
            ->latest('candidate_module.created_at') // si table pivot candidate_module
            ->get();

        // Quiz en attente (ex: statut 'assigned' ou non complété)
        $this->pendingQuizzes = $this->candidate->quizzes()
            ->wherePivot('status', 'assigned')
            ->with('module')
            ->orderBy('quizzes.created_at', 'desc')
            ->get();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function setInvoiceFilter(string $filter): void
    {
        $this->invoiceFilter = $filter;
        $this->loadData();
    }

    public function payInvoice(int $invoiceId): void
    {
        $invoice = $this->candidate->invoices()->whereKey($invoiceId)->firstOrFail();

        if ($invoice->status === 'paid') {
            $this->dispatchBrowserEvent('toast', ['type' => 'info', 'msg' => 'Cette facture est déjà payée.']);
            return;
        }

        // 👉 Ici intègre ton flux de paiement (redirection vers un provider, init session, etc.)
        // Pour la démo, on marque en "paid".
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->loadData();
        session()->flash('status', 'Paiement enregistré avec succès.');
    }

    public function saveProfile(): void
    {
        $this->validate([
            'firstname' => ['required', 'string', 'max:120'],
            'lastname'  => ['required', 'string', 'max:120'],
            'email'     => ['required', 'email', Rule::unique('candidates','email')->ignore($this->candidate->id)],
            'phone'     => ['nullable', 'string', 'max:60'],
            'birthdate' => ['nullable', 'date'],
            'birthplace'=> ['nullable', 'string', 'max:190'],
            'profile_photo' => ['nullable', 'image','max:2048'],
        ]);

        $path = $this->candidate->profile_photo_path;
        if ($this->profile_photo) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            $path = $this->profile_photo->store('profiles', 'public');
        }

        $this->candidate->update([
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'birthdate' => $this->birthdate ?: null,
            'birthplace'=> $this->birthplace,
            'profile_photo_path' => $path,
        ]);

        session()->flash('status', 'Profil mis à jour.');
    }

    public function savePassword(): void
    {
        $this->validate([
            'old_password' => ['required'],
            'password' => ['required','min:8','confirmed'],
        ], [], [
            'password' => 'mot de passe',
        ]);

        if (!Hash::check($this->old_password, $this->candidate->password)) {
            $this->addError('old_password', 'Ancien mot de passe incorrect.');
            return;
        }

        $this->candidate->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['old_password','password','password_confirmation']);
        session()->flash('status', 'Mot de passe modifié.');
    }

    public function render()
    {
        $stats = [
            'modules' => $this->candidate->modules()->count(),
            'invoices_pending' => $this->candidate->invoices()->where('status','pending')->count(),
            'invoices_paid'    => $this->candidate->invoices()->where('status','paid')->count(),
            'quizzes_pending'  => $this->candidate->quizzes()->wherePivot('status','assigned')->count(),
        ];

        return view('livewire.candidat.personnel-file', [
            'stats' => $stats,
        ]);
    }
}

