<?php

namespace App\Livewire\Sectiondash;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Mail\MailInfoCompte;
use Livewire\WithPagination;
use App\Models\Helper_function;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Prof extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    /** Listing & filtres */
    public string $search = '';
    public string $statusFilter = '';
    public string $cityFilter = '';
    public array $cities = [];
    public array $stats = [
        'count' => 0,
        'active' => 0,
        'blocked' => 0,
        'with_photo' => 0,
    ];

    /** Formulaire (sidebar) */
    public string $action = 'save'; // save|edit
    public ?int $editingId = null;
    public ?User $user = null;

    public ?string $firstname = null;
    public ?string $lastname = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $city = null;
    public ?string $address = null;
    public ?string $birthdate = null;
    public ?string $birthplace = null;
    public ?string $program = null;
    public ?string $level = null;
    public ?string $type_inscription = null;
    public ?string $type_formation = null;
    public ?string $message = null;
    public bool $is_actif = true;

    /** Photo */
    public $profile_photo_path; // TemporaryUploadedFile|null
    public ?string $profile_photo_url = null;

    /** Affichage mot de passe (pour changePassword) */
    public ?string $password = null;

    /** Divers */
    public string $currentUrl = '';

    /** Reset pagination quand on filtre */
    public function updatedSearch()        { $this->resetPage(); }
    public function updatedStatusFilter()  { $this->resetPage(); }
    public function updatedCityFilter()    { $this->resetPage(); }

    public function mount(): void
    {
        $this->currentUrl = url()->current();
        $this->loadCities();
        $this->computeStats();
    }

    public function render()
    {
        $query = User::query()
            ->where('statut', 'professeur');

        // recherche
        if (trim($this->search) !== '') {
            $s = '%' . mb_strtolower(trim($this->search)) . '%';
            $query->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(lastname) LIKE ?', [$s])
                  ->orWhereRaw('LOWER(firstname) LIKE ?', [$s])
                  ->orWhereRaw('LOWER(email) LIKE ?', [$s])
                  ->orWhereRaw('LOWER(phone) LIKE ?', [$s])
                  ->orWhereRaw('LOWER(code) LIKE ?', [$s]);
            });
        }

        // statut
        if ($this->statusFilter === 'active') {
            $query->where('is_actif', 1);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('is_actif', 0);
        }

        // ville
        if ($this->cityFilter !== '') {
            $query->where('city', $this->cityFilter);
        }

        $users = $query
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->paginate(10)
            ->through(function (User $u) {
               
                return [
                    'id' => $u->id,
                    'code' => $u->code,
                    'firstname' => $u->firstname,
                    'lastname' => $u->lastname,
                    'email' => $u->email,
                    'phone' => $u->phone,
                    'city' => $u->city,
                    'program' => $u->program,
                    'level' => $u->level,
                    'is_actif' => (bool)$u->is_actif,
                    'profile_photo_url' => $u->profile_photo_path
                        //? Storage::disk('public')->url($u->profile_photo_path)
                        ? route('docs.display', ['filename' => str_replace('profils/', '', $u->profile_photo_path)])
                        : null,
                ];
            });

        // rafraîchir stats (léger, mais si tu veux optimiser, fais-le côté mount + listeners)
        $this->computeStats();

        return view('livewire.sectiondash.prof', [
            'users' => $users,
        ]);
    }

    /** ========= Validation ========= */
    protected function rules(): array
    {
        $emailRule = 'required|email|max:150|unique:users,email';
        if ($this->action === 'edit' && $this->user) {
            $emailRule = 'required|email|max:150|unique:users,email,' . $this->user->id;
        }

        $uniquePhone = [
            'required',
            'phone:CG',
            function ($attribute, $value, $fail) {
                $tel = Helper_function::phone($value);
                $q = User::where('phone', $tel);
                if ($this->action === 'edit' && $this->user) {
                    $q->where('id', '!=', $this->user->id);
                }
                if ($q->exists()) {
                    $fail(__('Change le numéro de téléphone'));
                }
            },
        ];

        $base = [
            'profile_photo_path' => 'required|image|max:5120',
            'firstname' => 'required|string|max:100',
            'lastname'  => 'required|string|max:100',
            'birthdate' => 'required|date',
            'birthplace'=> 'required|string|max:150',
            'phone'     => $uniquePhone,
            'email'     => $emailRule,
            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:120',
            'level'     => 'nullable|string|max:120',
            'program'   => 'nullable|string|max:255',
            'message'   => 'nullable|string|max:1000',
            'type_inscription' => 'nullable|string|max:120',
            'type_formation'   => 'nullable|string|max:120',
        ];

        if ($this->action === 'edit') {
            $base['is_actif'] = 'required|boolean';
        }

        return $base;
    }

    /** ========= Actions listing ========= */

    public function startEdit(int $id): void
    {
        $u = User::where('id', $id)->where('statut', 'professeur')->first();
        if (!$u) {
            $this->dispatch('toast', type: 'error', message: 'Professeur introuvable');
            return;
        }

        $this->user = $u;
        $this->editingId = $u->id;
        $this->action = 'edit';

        // hydrate formulaire
        $this->firstname = $u->firstname;
        $this->lastname = $u->lastname;
        $this->email = $u->email;
        $this->phone = $u->phone;
        $this->city = $u->city;
        $this->address = $u->address;
        $this->birthdate = $u->birthdate ? (string)$u->birthdate : null;
        $this->birthplace = $u->birthplace;
        $this->program = $u->program;
        $this->level = $u->level;
        $this->type_inscription = $u->type_inscription;
        $this->type_formation = $u->type_formation;
        $this->message = $u->message;
        $this->is_actif = (bool)$u->is_actif;
        $this->profile_photo_url = $u->profile_photo_path
            ? route('docs.display', ['filename' => str_replace('profils/', '', $u->profile_photo_path)])
            : null;

        $this->password = null; // affichage éventuel après changePassword()
    }

    public function toggleActive(int $id): void
    {
        $u = User::where('id', $id)->where('statut', 'professeur')->first();
        if (!$u) {
            $this->dispatch('toast', type: 'error', message: 'Professeur introuvable');
            return;
        }
        $u->is_actif = !$u->is_actif;
        $u->save();

        $this->dispatch('toast', type: 'success', message: $u->is_actif ? 'Compte débloqué' : 'Compte bloqué');

        // si on édite ce user
        if ($this->user && $this->user->id === $u->id) {
            $this->is_actif = (bool)$u->is_actif;
            $this->user->refresh();
        }
        // refresh page courante
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $u = User::where('id', $id)->where('statut', 'professeur')->first();
        if (!$u) {
            $this->dispatch('toast', type: 'error', message: 'Professeur introuvable');
            return;
        }
        $u->delete();

        // si on supprimait celui en cours d’édition
        if ($this->editingId === $id) {
            $this->resetForm();
        }

        $this->dispatch('toast', type: 'success', message: 'Supprimé avec succès');
        $this->resetPage();
    }

    /** ========= Actions formulaire ========= */

    public function save()
    {
        $this->action = 'save';
        $data = $this->validate();

        $data['phone'] = Helper_function::phone($data['phone']);
        $data['statut'] = 'professeur';
        $data['role'] = 'professeur';
        $data['is_actif'] = true;

        if ($this->profile_photo_path) {
            //$data['profile_photo_path'] = $this->profile_photo_path->store('profile-photos', 'public');
            $data['profile_photo_path'] = $this->profile_photo_path;
        }

        if (method_exists(User::class, 'SaveData')) {
            $res = User::SaveData($data, 'professeur');
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => $res['message'] ?? 'Créé avec succès',]);

            // Envoi des identifiants s’ils sont renvoyés
            if (!empty($res['password'] ?? null)) {
                $mailData = [
                    'title' => 'Création de compte réalisée avec succès',
                    'email' => $res['data']['email'] ?? $data['email'],
                    'fullname' => ($res['data']['firstname'] ?? $data['firstname']) . ' ' . ($res['data']['lastname'] ?? $data['lastname']),
                    'pwd' => $res['password'],
                ];
                Helper_function::send_mail(new MailInfoCompte($mailData), $mailData['email']);
            }
        } else {
            // fallback
           
            User::create($data);
           
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => 'Créé avec succès',]);
        }

        $this->resetForm();
        $this->resetPage();
        $this->loadCities();
        $this->computeStats();
    }

    public function edit()
    {
        if (!$this->user) {
            $this->dispatch('toast', type: 'error', message: "Sélectionne d'abord un professeur");
            return;
        }

        $this->action = 'edit';
        $data = $this->validate();

        $data['phone'] = Helper_function::phone($data['phone']);

        if ($this->profile_photo_path) {
            //$data['profile_photo_path'] = $this->profile_photo_path->store('profile-photos', 'public');
            $data['profile_photo_path'] = $this->profile_photo_path;
        }

        if (method_exists(User::class, 'EditData')) {
            $res = User::EditData($this->user->id, $data, $this->user);
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => $res['message'] ?? 'Mise à jour effectuée',
                //'text'   => 'Une erreur est survenue lors de la vérification.',
                
            ]);
            //$this->dispatch('toast', type: 'success', message: $res['message'] ?? 'Mise à jour effectuée');
        } else {
            $this->user->update($data);
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => $res['message'] ?? 'Mise à jour effectuée',
                //'text'   => 'Une erreur est survenue lors de la vérification.',
               
            ]);
            //$this->dispatch('toast', type: 'success', message: 'Mise à jour effectuée');
        }

        // garder l’édition ouverte, rafraîchir preview
        $this->profile_photo_url = !empty($data['profile_photo_path'] ?? null)
            ? route('docs.display', ['filename' => str_replace('profils/', '', $data['profile_photo_path'])])
            : $this->profile_photo_url;

        $this->reset(['firstname','lastname','email','phone','city','address',
        'birthdate','birthplace','program','level',
        'type_inscription','type_formation','message',
        'is_actif','profile_photo_path','profile_photo_url',
        'password','user','editingId',]);
        $this->loadCities();
        $this->computeStats();
    }

    /** Génère un mdp temporaire, l’applique au compte et l’affiche dans l’UI */
    public function changePassword(int $id): void
    {
        $u = User::where('id', $id)->where('statut', 'professeur')->first();
        if (!$u) {
            $this->dispatch('toast', type: 'error', message: 'Professeur introuvable');
            return;
        }
        $temp = Str::random(10);
        $u->password = Hash::make($temp);
        $u->save();

        // si on est en cours d’édition, afficher
        if ($this->user && $this->user->id === $u->id) {
            $this->password = $temp;
        }

        $this->dispatch('toast', type: 'success', message: 'Mot de passe réinitialisé');
    }

    /** Option “Copier mdp” – ici on renvoie juste le dernier mdp temporaire si on est en édition */
    public function copyPassword(int $id): void
    {
        if ($this->editingId !== $id || !$this->password) {
            $this->dispatch('toast', type: 'warning', message: 'Aucun mot de passe temporaire à copier');
            return;
        }
        // À gérer côté JS (listener) pour copier dans le presse-papier
        $this->dispatch('copy-to-clipboard', text: $this->password);
        $this->dispatch('toast', type: 'success', message: 'Mot de passe copié');
    }

    /** ========= Helpers ========= */

    public function resetForm(): void
    {
        $this->reset([
            'action',
            'editingId',
            'user',
            'firstname',
            'lastname',
            'email',
            'phone',
            'city',
            'address',
            'birthdate',
            'birthplace',
            'program',
            'level',
            'type_inscription',
            'type_formation',
            'message',
            'is_actif',
            'profile_photo_path',
            'profile_photo_url',
            'password',
        ]);
        $this->action = 'save';
        $this->is_actif = true;
    }

    private function loadCities(): void
    {
        $this->cities = User::where('statut', 'professeur')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->toArray();
    }

    private function computeStats(): void
    {
        $base = User::where('statut', 'professeur');
        $this->stats['count'] = (clone $base)->count();
        $this->stats['active'] = (clone $base)->where('is_actif', 1)->count();
        $this->stats['blocked'] = (clone $base)->where('is_actif', 0)->count();
        $this->stats['with_photo'] = (clone $base)->whereNotNull('profile_photo_path')->count();
    }
}
