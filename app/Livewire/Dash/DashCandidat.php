<?php
// app/Http/Livewire/Dash/DashCandidat.php

namespace App\Livewire\Dash;

use App\Models\User;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Candidate;
use App\Models\CourseModule;
use Illuminate\Support\Facades\Auth;

class DashCandidat extends Component
{
    public User $candidate;

    // Data blocs
    public $stats = [
        'quiz_progress_pct' => 0,
        'paid_count'        => 0,
        'total_invoices'    => 0,
        'modules_count'     => 0,
        'pending_quizzes'   => 0,
    ];

    public $pendingQuizzes;     // collection
    public $presentModules;     // collection (modules inscrits/présents)
    public $pendingInvoices;    // collection

    public function mount(): void
    {
        // Récupère le candidat connecté (adapte si tu as un guard dédié)
        $user = Auth::user();
       
        if ($user instanceof User) {
            $this->candidate = $user;
        } else {
            // Si ton User ≠ Candidate, ajoute une relation ->candidate
            $this->candidate = $user->candidate ?? User::where('id', $user->id)->firstOrFail();
        }

        $this->hydrateData();
    }

    protected function hydrateData(): void
    {
        // Invoices
        $paidCount   = $this->candidate->invoices()->where('status','paid')->count();
        $totalInv    = $this->candidate->invoices()->count();
        $this->pendingInvoices = $this->candidate->invoices()
            ->where('status','unpaid')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Modules
        $this->presentModules = CourseModule::get();
        /* $this->presentModules = $this->candidate->modules()
            ->withCount('contents')
            //->with('teachers') // si tu as relation ->teachers() sur Module, sinon retire
            ->latest('candidate_module.created_at')
            ->limit(12)
            ->get(); */
        //$modulesCount = $this->candidate->modules()->count();
        $modulesCount = CourseModule::count();

        // Quizzes
       /*  $totalQuizzes   = $this->candidate->quizzes()->count();
        $completedCount = $this->candidate->quizzes()->wherePivot('status','completed')->count();
        $this->pendingQuizzes = $this->candidate->quizzes()
            ->wherePivot('status','assigned')
            ->with('module')
            ->latest('candidate_quiz.created_at')
            ->limit(12)
            ->get(); 
            $pendingCount = $this->candidate->quizzes()->wherePivot('status','assigned')->count();
            */
        $totalQuizzes   = 0;
        $completedCount = 0;
        $this->pendingQuizzes = [];

        $pendingCount = 0;

        $pct = $totalQuizzes > 0 ? round(($completedCount / $totalQuizzes) * 100) : 0;

        $this->stats = [
            'quiz_progress_pct' => 0,
            'paid_count'        => $paidCount,
            'total_invoices'    => $totalInv,
            'modules_count'     => $modulesCount,
            'pending_quizzes'   => $pendingCount,
        ];
    }

    public function payInvoice(int $invoiceId): void
    {
        $inv = $this->candidate->invoices()->whereKey($invoiceId)->firstOrFail();
        if ($inv->status === 'paid') return;

        // 👉 Branche ici ton gateway (init session, redirect, webhook, etc.)
        // Démo : marquer payé
        $inv->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $this->hydrateData();
        session()->flash('status', 'Paiement enregistré.');
    }

    public function startQuiz(int $quizId)
    {
        // Redirige vers ta route de passage du quiz
        return redirect()->route('quizzes.take', [$quizId, 'candidate' => $this->candidate->id]);
    }

    public function render()
    {
        return view('livewire.dash.dash-candidat');
    }
}
