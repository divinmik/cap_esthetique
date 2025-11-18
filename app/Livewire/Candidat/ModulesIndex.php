<?php

// app/Livewire/Candidat/ModulesIndex.php
namespace App\Livewire\Candidat;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\CourseModule;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ModulesIndex extends Component
{
    use WithPagination;

    public string $q = ''; // recherche

    public function updatingQ() { $this->resetPage(); }

    public function render()
    {
        // Regroupement par titre : nb profs distincts + total contenus
        $rows = CourseModule::query()
            ->when($this->q !== '', fn($q) =>
                $q->where(function($w){
                    $w->where('title', 'like', '%'.$this->q.'%')
                      ->orWhere('description','like','%'.$this->q.'%');
                })
            )
            ->select([
                'title',
                'code',
                DB::raw('COUNT(DISTINCT user_id) as total_profs'),
                DB::raw('SUM(contents_count) as total_contents'),
                DB::raw('MIN(created_at) as first_created_at'),
            ])
            ->groupBy('title', 'code')
            ->orderByDesc(DB::raw('SUM(contents_count)'))
            ->paginate(12);

        return view('livewire.candidat.modules-index', [
            'groups' => $rows,
            'str' => new Str(), // pour slug
        ]);
    }
}
