@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    function avatar_url($userPhoto = null, $inscriptionPhoto = null) {
        $path = $userPhoto ?: $inscriptionPhoto;
        return $path ? Storage::url($path) : null;
    }
@endphp

<div class="row">
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">

                    {{-- KPI: Total profs --}}
                    <div class="col-md-3">
                        <div class="card shadow-lg p-3 h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-user-tie"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total prof.</p>
                                        <h4 class="mb-0">{{ $totalProf }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: Total candidats --}}
                    <div class="col-md-3">
                        <div class="card shadow-lg p-3 h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-user-graduate"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total candidat</p>
                                        <h4 class="mb-0">{{ $totalCandidat }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: Total modules --}}
                    <div class="col-md-3">
                        <div class="card shadow-lg p-3 h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-layer-group"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total module</p>
                                        <h4 class="mb-0">{{ $totalModules }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KPI: Factures impayées --}}
                    <div class="col-md-3">
                        <div class="card shadow-lg p-3 h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="far fa-file-invoice-dollar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Facture en attente</p>
                                        <h4 class="mb-0">{{ $unpaidCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- /row --}}
            </div>
        </div>
    </div>

    {{-- MODULES (gauche) --}}
    <div class="col-xxl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card simplebar-scrollable-y" style="height: 416px; overflow: hidden auto;" data-simplebar>
                    <div class="card-header card-header-bordered d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-icon text-muted"><i class="fa fa-folder-open fs-14"></i></div>
                            <h3 class="card-title mb-0">
                               Modules enregistrés
                            </h3>
                        </div>

                        
                    </div>

                    <div class="card-body">
                        <div class="accordion" id="modulesAccordion">
                            @forelse($modules as $m)
                                @php
                                    $count = $contentCountPerModule[$m->id] ?? 0;
                                    $types = $typeBreakdown->where('course_module_id', $m->id)
                                                            ->map(fn($r) => $r->type.' ('.$r->total.')')
                                                            ->implode(', ');
                                    $badge = Str::of($m->title ?? 'M')->substr(0,1)->upper();
                                    $collapseId = 'mod-'.$m->id;
                                @endphp

                                <div class="accordion-item border-0 border-bottom">
                                    <h2 class="accordion-header" id="heading-{{ $m->id }}">
                                        <button class="accordion-button collapsed py-3" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $collapseId }}">
                                            <div class="d-flex w-100 align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width:40px;height:40px;">
                                                        <span class="fw-bold text-primary">{{ $badge }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 text-start">
                                                    <div class="fw-semibold">{{ $m->title ?? ('Module #'.$m->id) }}</div>
                                                    <div class="small text-muted">
                                                        Contenus: <strong>{{ $count }}</strong> &nbsp;•&nbsp;
                                                        Types: {{ $types ?: '—' }} &nbsp;•&nbsp;
                                                        Créé le: {{ optional($m->created_at)->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    <span class="badge rounded-pill text-bg-light border">#{{ $m->id }}</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>

                                    <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $m->id }}" data-bs-parent="#modulesAccordion">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width:70px;">#</th>
                                                            <th style="width:120px;">Type</th>
                                                            <th>Titre</th>
                                                            <th style="width:340px;">Fichier / Lien</th>
                                                            <th style="width:160px;">Créé le</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            // Si la relation n'est pas eager-load, remplacez par votre variable existante
                                                            $contents = $m->contents ?? collect();
                                                        @endphp

                                                        @forelse($contents as $c)
                                                            <tr>
                                                                <td>{{ $c->id }}</td>
                                                                <td><span class="badge rounded-pill text-bg-light border">{{ $c->type }}</span></td>
                                                                <td>{{ $c->title ?? '—' }}</td>
                                                                <td class="text-truncate" style="max-width:320px;">
                                                                    @if(!empty($c->file_path))
                                                                        <a class="link-underline-primary" target="_blank" href="{{ Storage::url($c->file_path) }}">Fichier</a>
                                                                    @elseif(!empty($c->url))
                                                                        <a class="link-underline-primary" target="_blank" href="{{ $c->url }}">Lien</a>
                                                                    @else
                                                                        <span class="text-muted">—</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted py-4">Aucun contenu</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>

                                            {{-- Pagination par module si vous utilisez une pagination par relation (facultatif) --}}
                                            {{-- <div class="mt-2">{{ $contents->links() }}</div> --}}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Aucun module</div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            {{ $modules->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- FACTURES IMPAYEES (droite) --}}
    <div class="col-xxl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card simplebar-scrollable-y" style="height: 416px; overflow: hidden auto;" data-simplebar>
                    <div class="card-header card-header-bordered d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="card-icon text-muted"><i class="fa fa-file-invoice-dollar fs-14"></i></div>
                            <h3 class="card-title mb-0">Factures en attente</h3>
                            <span class="badge rounded-pill text-bg-light border ms-2">{{ $unpaidCount }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="rich-list rich-list-flush">
                            <div class="flex-column align-items-stretch">
                                @forelse($unpaidInvoices as $inv)
                                    @php
                                        $nom = trim(($inv->user_firstname ?? '').' '.($inv->user_lastname ?? '')) ?: '—';
                                        $mois = optional($inv->created_at)->translatedFormat('F Y');
                                    @endphp
                                    <div class="rich-list-item py-2 border-bottom">
                                        <div class="rich-list-prepend">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2"
                                                 style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                                <span class="fw-bold text-primary">{{ Str::of($nom)->substr(0,1)->upper() }}</span>
                                            </div>
                                        </div>
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title mb-1">{{ $nom }}</h4>
                                            <p class="rich-list-subtitle mb-0">Mois: {{ $mois }}</p>
                                            <p class="rich-list-subtitle mb-0">Montant: {{ number_format($inv->amount ?? 0, 0, ',', ' ') }} {{ $inv->currency ?? 'XAF' }}</p>
                                            <p class="rich-list-subtitle mb-0">Status:  @if($inv->status==='paid')
                                                    <span class="text-success">Payé</span>
                                                @elseif($inv->status==='partial')
                                                    <span class="text-info">Partiel</span>
                                                @elseif($inv->status==='cancelled')
                                                    <span class="text-secondary">Annulée</span>
                                                @else
                                                    <span class="text-danger">Impayé</span>
                                                @endif</p>
                                        
                                        </div>
                                        <div class="rich-list-append">
                                            <button class="btn btn-sm btn-label-primary">relance</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted">Aucune facture impayée</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $unpaidInvoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INSCRIPTIONS EN ATTENTE DE CONFIRMATION --}}
    <div class="col-12">
        <div class="card" style="height: 495px; overflow: hidden auto;" data-simplebar>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="card-icon text-muted"><i class="fas fa-sync-alt fs-14"></i></div>
                    <h3 class="card-title mb-0">
                        Inscription en attente de confirmation de paiement
                    </h3>
                    <div class="avatar avatar-primary avatar-circle avatar-xs ms-2">
                        <span class="avatar-display">{{ $unconfirmedInscriptions }}</span>
                    </div>
                </div>
                <div class="card-addon dropdown">
                    <button class="btn btn-label-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        Filtres <i class="fas fa-filter fs-12 align-middle ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated">
                        <a class="dropdown-item" href="javascript:void(0)">Aujourd'hui</a>
                        <a class="dropdown-item" href="javascript:void(0)">Hier</a>
                        <a class="dropdown-item" href="javascript:void(0)">Semaine</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('id')" class="cursor-pointer">#</th>
                                <th>Code</th>
                                <th>Profil</th>
                                <th wire:click="sortBy('lastname')" class="cursor-pointer">Nom</th>
                                <th>Prénom</th>
                                <th>Date de naissance</th>
                                <th wire:click="sortBy('email')" class="cursor-pointer">Email</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th>Ville Choisie</th>
                                <th>Type de formation</th>
                                <th>Niveau atteint</th>
                                <th>Contact salon partenaire</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($unconfirmedInscriptionsList as $ins)
                                @php $photo = avatar_url(null, $ins->inscription_photo ?? null); @endphp
                                <tr>
                                    <td>{{ $ins->id }}</td>
                                    <td>{{ 'INS-'.str_pad($ins->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center"
                                                 style="width:36px;height:36px;background:#f3f4f6;">
                                                @if($photo)
                                                    <img src="{{ $photo }}" alt="avatar" class="w-100 h-100 object-fit-cover"/>
                                                @else
                                                    <span class="small text-muted">{{ Str::of($ins->firstname ?? '')->substr(0,1)->upper() }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">{{ $ins->lastname ?? '—' }}</td>
                                    <td>{{ $ins->firstname ?? '—' }}</td>
                                    <td>{{ $ins->birthdate ? Carbon::parse($ins->birthdate)->format('d/m/Y') : '—' }}</td>
                                    <td>{{ $ins->email ?? '—' }}</td>
                                    <td>{{ $ins->phone ?? '—' }}</td>
                                    <td>{{ $ins->address ?? '—' }}</td>
                                    <td>{{ $ins->city ?? '—' }}</td>
                                    <td>{{ $ins->type_formation ?? '—' }}</td>
                                    <td>{{ $ins->level ?? '—' }}</td>
                                    <td>{{ $ins->contact_salon ?? '—' }}</td>
                                    <td><span class="badge rounded-pill text-bg-light border">{{ $ins->statut ?? '—' }}</span></td>
                                    <td>{{ optional($ins->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary">Voir</button>
                                            <button class="btn btn-outline-success">Valider</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="16" class="text-center text-muted py-4">Aucune inscription non confirmée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                {{ $unconfirmedInscriptionsList->links() }}
            </div>
        </div>
    </div>
</div>
