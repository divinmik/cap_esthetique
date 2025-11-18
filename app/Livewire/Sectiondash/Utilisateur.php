<?php

namespace App\Livewire\Sectiondash;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Utilisateur extends Component
{
    use WithFileUploads;

    // Fake store en mémoire
    /** @var array<int, array> */
    public array $users = [];

    // Filtres/recherche
    public string $search = '';
    public ?string $roleFilter = null;    // admin|manager|staff|candidate|null
    public ?string $statusFilter = null;  // active|blocked|null

    // Formulaire
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $role = 'candidate';
    public $photo; // Livewire temp uploaded file
    public ?string $avatar_path = null; // chemin stocké (storage public) ou null
    public ?string $password = null;    // seulement pour affichage/copie (fake)

    // Liste de rôles (référence)
    public array $roles = ['admin','finance','staff','candidate'];

    public function mount(): void
    {
        // Seed FAKE
        $make = function (int $id, string $name, string $email, string $role, string $status = 'active', ?string $phone = null) {
            $pwd = Str::password(10, symbols: false); // mot de passe visible (fake)
            return [
                'id'          => $id,
                'name'        => $name,
                'email'       => $email,
                'phone'       => $phone ?? '06'.rand(1000000,9999999),
                'role'        => $role,
                'status'      => $status,   // active|blocked
                'avatar_path' => null,      // asset('storage/avatars/xxx.jpg') si uploadé
                'password'    => $pwd,      // on garde le clair pour copier (fake only)
            ];
        };

        $this->users = [
            $make(1,'Mbelolo Kossi','mbelolo@example.com','admin','active'),
            $make(2,'Nadia Mbemba','nadia@example.com','manager','active'),
            $make(3,'Ruben Diallo','ruben@example.com','staff','blocked'),
            $make(4,'Sarah Sita','sarah@example.com','candidate','active'),
            $make(5,'Achille Tchicaya','achille@example.com','staff','active'),
            $make(6,'Yasmina Ndinga','yasmina@example.com','candidate','active'),
        ];
    }

    // ---------- Helpers ----------
    private function nextId(): int
    {
        return (collect($this->users)->max('id') ?? 0) + 1;
    }

    private function filtered(): array
    {
        $list = $this->users;

        if ($this->search !== '') {
            $q = Str::lower($this->search);
            $list = array_values(array_filter($list, function ($u) use ($q) {
                return Str::contains(Str::lower($u['name']), $q)
                    || Str::contains(Str::lower($u['email']), $q)
                    || Str::contains(Str::lower((string)$u['phone']), $q)
                    || Str::contains(Str::lower($u['role']), $q);
            }));
        }
        if ($this->roleFilter) {
            $list = array_values(array_filter($list, fn($u) => $u['role'] === $this->roleFilter));
        }
        if ($this->statusFilter) {
            $list = array_values(array_filter($list, fn($u) => $u['status'] === $this->statusFilter));
        }

        usort($list, fn($a,$b)=> strcmp($a['name'],$b['name']));
        return $list;
    }

    public function getStatsProperty(): array
    {
        $all = $this->filtered();
        return [
            'count'   => count($all),
            'active'  => count(array_filter($all, fn($u)=> $u['status']==='active')),
            'blocked' => count(array_filter($all, fn($u)=> $u['status']==='blocked')),
            'admins'  => count(array_filter($all, fn($u)=> $u['role']==='admin')),
        ];
    }

    private function resetForm(): void
    {
        $this->reset(['editingId','name','email','phone','role','photo','avatar_path','password']);
        $this->role = 'candidate';
    }

    // ---------- CRUD & Actions ----------
    public function create(): void
    {
        $this->validate([
            'name'  => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'role'  => 'required|in:admin,manager,staff,candidate',
            'photo' => 'nullable|image|max:2048',
        ]);

        $id  = $this->nextId();
        $pwd = Str::password(10, symbols: false);

        $path = null;
        if ($this->photo) {
            // Stockage public: storage/app/public/avatars
            $path = $this->photo->store('avatars','public');
            $path = asset('storage/'.$path);
        }

        $this->users[] = [
            'id'          => $id,
            'name'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'role'        => $this->role,
            'status'      => 'active',
            'avatar_path' => $path,
            'password'    => $pwd,
        ];

        $this->resetForm();
        $this->dispatch('swal', title: 'Créé', text: 'Utilisateur créé avec succès', icon: 'success');
    }

    public function startEdit(int $id): void
    {
        $u = collect($this->users)->firstWhere('id',$id);
        if (!$u) return;

        $this->editingId  = $u['id'];
        $this->name       = $u['name'];
        $this->email      = $u['email'];
        $this->phone      = $u['phone'];
        $this->role       = $u['role'];
        $this->avatar_path= $u['avatar_path'];
        $this->password   = $u['password']; // pour affichage/copie
    }

    public function update(): void
    {
        $this->validate([
            'name'  => 'required|string|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'role'  => 'required|in:admin,manager,staff,candidate',
            'photo' => 'nullable|image|max:2048',
        ]);

        foreach ($this->users as &$u) {
            if ($u['id'] === $this->editingId) {
                $u['name']  = $this->name;
                $u['email'] = $this->email;
                $u['phone'] = $this->phone;
                $u['role']  = $this->role;

                if ($this->photo) {
                    $path = $this->photo->store('avatars', 'public');
                    $u['avatar_path'] = asset('storage/'.$path);
                }
                break;
            }
        }
        $this->resetForm();
        $this->dispatch('swal', title: 'Mis à jour', text: 'Profil utilisateur mis à jour', icon: 'success');
    }

    public function delete(int $id): void
    {
        $this->users = array_values(array_filter($this->users, fn($u)=> $u['id'] !== $id));
        if ($this->editingId === $id) $this->resetForm();
        $this->dispatch('swal', title: 'Supprimé', text: 'Utilisateur supprimé', icon: 'success');
    }

    public function toggleBlock(int $id): void
    {
        foreach ($this->users as &$u) {
            if ($u['id'] === $id) {
                $u['status'] = $u['status'] === 'active' ? 'blocked' : 'active';
                $state = $u['status'] === 'blocked' ? 'bloqué' : 'débloqué';
                $this->dispatch('swal', title: 'État changé', text: "Compte {$state}", icon: 'info');
                break;
            }
        }
    }

    public function copyPassword(int $id): void
    {
        $u = collect($this->users)->firstWhere('id',$id);
        if (!$u) return;

        $this->dispatch('copy-pwd', text: $u['password']);
    }

    public function changePassword(int $id): void
    {
        foreach ($this->users as &$u) {
            if ($u['id'] === $id) {
                $u['password'] = Str::password(12, symbols:false);
                $this->dispatch('swal', title: 'Mot de passe régénéré', text: "Nouveau: {$u['password']}", icon: 'success');
                // Propose copie auto
                $this->dispatch('copy-pwd', text: $u['password']);
                break;
            }
        }
    }

    public function render()
    {
        $users = $this->filtered();
        $stats = $this->stats;

        return view('livewire.sectiondash.utilisateur', compact('users','stats'))
            ->title('Gestion des utilisateurs (Fake data)');
    }
}
