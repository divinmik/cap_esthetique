<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inscription;
use Livewire\WithPagination;

class Liste extends Component
{
   use WithPagination;

    public $search = '';
    public $password;
    public $statutPassword;
    public $candidates;
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['candidateAdded' => '$refresh'];

    protected $queryString = ['search', 'perPage', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteCandidate($id)
    {
        $c = Inscription::find($id);
        if (!$c) {
            session()->flash('error', 'Candidat introuvable.');
            return;
        }

        // optionally remove files from storage
        if ($c->signature_path && \Storage::exists($c->signature_path)) {
            \Storage::delete($c->signature_path);
        }
        if ($c->profile_photo_path && \Storage::exists($c->profile_photo_path)) {
            \Storage::delete($c->profile_photo_path);
        }

        $c->delete();
        session()->flash('message', 'Candidat supprimé.');
        $this->resetPage();
    }

    public function getPassword()
    {
        if($this->password == "AdminCompte1234@#"){
            $this->statutPassword = true;
        }else{
            session()->flash('message', 'Mot de passe incorrecte');
            session()->flash('status', 'error'); // ou 'error'
            $this->statutPassword = false;
        }

    }
    
    public function render()
    {
        $this->candidates = Inscription::get();

        return view('livewire.liste');
    }
}
