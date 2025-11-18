<?php

namespace App\Livewire\Dash;

use App\Models\User;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Inscription;
use App\Models\CourseModule;

use Livewire\WithPagination;
use App\Models\ModuleContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminDashboard extends Component
{
    use WithPagination;

    // Etats UI
    public string $searchInvoice = '';
    public ?int $moduleId = null;            // détail module
    public string $contentTypeFilter = '';   // filtre type contenu module

    // Paginations indépendantes
    public int $perPageInvoices     = 10;
    public int $perPageModules      = 10;
    public int $perPageContents     = 10;
    public int $perPageInscriptions = 10;

    // Tri inscriptions en attente
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'searchInvoice'     => ['except' => ''],
        'moduleId'          => ['except' => null],
        'contentTypeFilter' => ['except' => ''],
    ];

    // Reset des pages quand filtres changent
    public function updatedSearchInvoice()       { $this->resetPage('invoices'); }
    public function updatedContentTypeFilter()   { $this->resetPage('moduleContents'); }
    public function updatedPerPageInvoices()     { $this->resetPage('invoices'); }
    public function updatedPerPageModules()      { $this->resetPage('modules'); }
    public function updatedPerPageContents()     { $this->resetPage('moduleContents'); }
    public function updatedPerPageInscriptions() { $this->resetPage('inscriptions'); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage('inscriptions');
    }

    // ===== AGGREGATS / TOTALS =====
    protected function aggregates(): array
    {
        // Totaux simples (selon tes colonnes role/statut côté users)
        $totalProf      = User::where('role', 'professeur')->count();
        $totalCandidat  = User::where('role', 'candidat')->count();
        $totalModules   = CourseModule::count();

        // Compte factures impayées (liées aux candidats)
        $unpaidCount = Invoice::query()
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->where('invoices.status', 'unpaid')
            ->where('users.role', 'candidat')
            ->count();

        // Compte contenus par module
        $contentCountPerModule = ModuleContent::query()
            ->select('course_module_id', DB::raw('COUNT(*) as total'))
            ->groupBy('course_module_id')
            ->pluck('total', 'course_module_id');

        // Détail des types par module (si tu as une colonne 'type')
        $typeBreakdown = ModuleContent::query()
            ->select('course_module_id', 'type', DB::raw('COUNT(*) as total'))
            ->groupBy('course_module_id', 'type')
            ->get();

        // Inscriptions non confirmées = pas de paiement SUCCESS
        $unconfirmedInscriptions = Inscription::query()
            ->leftJoin('momopaiements', function ($join) {
                $join->on('momopaiements.inscription_id', '=', 'inscriptions.id')
                     ->where('momopaiements.status', '=', 'SUCCESS');
            })
            ->whereNull('momopaiements.id')
            ->count();

        return compact(
            'totalProf',
            'totalCandidat',
            'totalModules',
            'unpaidCount',
            'contentCountPerModule',
            'typeBreakdown',
            'unconfirmedInscriptions'
        );
    }

    // ===== LISTES =====

    /** Factures impayées liées aux candidats + recherche + pagination dédiée */
    public function getUnpaidInvoicesProperty()
    {
        return Invoice::query()
            ->join('users', 'users.id', '=', 'invoices.user_id')
            ->where('invoices.status', 'unpaid')
            ->where('users.role', 'candidat')
            ->when($this->searchInvoice !== '', function ($q) {
                $term = '%'.str_replace(' ', '%', $this->searchInvoice).'%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('invoices.number', 'like', $term)
                       ->orWhere('invoices.reference', 'like', $term)
                       ->orWhere('users.lastname', 'like', $term)
                       ->orWhere('users.email', 'like', $term);
                });
            })
            ->select([
                'invoices.*',
                'users.firstname as user_firstname',
                'users.lastname as user_lastname',
                'users.email as candidate_email',
            ])
            ->latest('invoices.created_at')
            ->paginate($this->perPageInvoices, ['*'], 'invoices');
    }

    /** Modules list */
    public function getModulesProperty()
    {
        return CourseModule::query()
            ->withCount('contents')
            ->orderBy('title')
            ->paginate($this->perPageModules, ['*'], 'modules');
    }

    /** Contenus d’un module sélectionné (toujours renvoyer un paginator) */
    public function getModuleContentsProperty()
    {
        $currentPage = request()->input('moduleContents', 1);

        if (!$this->moduleId) {
            return new LengthAwarePaginator(
                items: [],
                total: 0,
                perPage: $this->perPageContents,
                currentPage: $currentPage,
                options: [
                    'path' => request()->url(),
                    'pageName' => 'moduleContents',
                ]
            );
        }

        return ModuleContent::query()
            ->where('course_module_id', $this->moduleId)
            ->when($this->contentTypeFilter !== '', fn($q) => $q->where('type', $this->contentTypeFilter))
            ->orderByDesc('created_at')
            ->paginate($this->perPageContents, ['*'], 'moduleContents');
    }

    /** Inscriptions non confirmées = pas de paiement SUCCESS */
    public function getUnconfirmedInscriptionsListProperty()
    {
        return Inscription::query()
            ->leftJoin('momopaiements', function ($join) {
                $join->on('momopaiements.inscription_id', '=', 'inscriptions.id')
                     ->where('momopaiements.status', '=', 'SUCCESS');
            })
            ->whereNull('momopaiements.id')
            ->select([
                'inscriptions.id',
                'inscriptions.created_at',
                'inscriptions.firstname',
                'inscriptions.lastname',
                'inscriptions.birthdate',
                'inscriptions.birthplace',
                'inscriptions.address',
                'inscriptions.phone',
                'inscriptions.email',
                'inscriptions.city',
                'inscriptions.level',
                'inscriptions.contact_salon',
                'inscriptions.program',
                'inscriptions.message',
                'inscriptions.type_inscription',
                'inscriptions.type_formation',
                'inscriptions.profile_photo_path as inscription_photo',
                'inscriptions.is_valide',
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPageInscriptions, ['*'], 'inscriptions');
    }

    // Actions
    public function clearModuleSelection()
    {
        $this->moduleId = null;
        $this->contentTypeFilter = '';
        $this->resetPage('moduleContents');
    }

    public function render()
    {
        $agg = $this->aggregates();
       
        return view('livewire.dash.admin-dashboard', array_merge($agg, [
            'unpaidInvoices'              => $this->unpaidInvoices,
            'modules'                     => $this->modules,
            'moduleContents'              => $this->moduleContents,
            'unconfirmedInscriptionsList' => $this->unconfirmedInscriptionsList,
        ]));
    }
}
