<div class="row">
    <div class="col-xxl-9">
        <div class="card ">
            <div class="card-body">
                <div class="row justify-content-center">

                    {{-- Progression Quiz --}}
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Progression Quiz</p>
                                        <h4 class="mb-0">{{ $stats['quiz_progress_pct'] }}%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nombre paiement --}}
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Nombre paiement</p>
                                        <h4 class="mb-0">{{ $stats['paid_count'] }}/{{-- $stats['total_invoices'] --}}9</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total module disponible --}}
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-folder-plus"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total module disponible</p>
                                        <h4 class="mb-0">{{ $stats['modules_count'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quiz en attente (count) --}}
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Quiz en attente</p>
                                        <h4 class="mb-0">{{ $stats['pending_quizzes'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- row cards --}}
            </div>
        </div>

        {{-- Quiz en attente de réalisation --}}
        <div class="row">
            <div class="col-12">
                <div class="card" style="height: 495px; overflow: hidden auto;" data-simplebar="">
                    <div class="card-header">
                        <div class="card-icon text-muted"><i class="fas fa-sync-alt fs-14"></i></div>
                        <h3 class="card-title">Quiz en attente de réalisation</h3>
                        <div class="card-addon dropdown">
                            <button class="btn btn-label-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-filter fs-12 align-middle ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated">
                                <a class="dropdown-item" href="#"><div class="dropdown-icon"><i class="fa fa-poll"></i></div><span class="dropdown-content">Today</span></a>
                                <a class="dropdown-item" href="#"><div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div><span class="dropdown-content">Yesterday</span></a>
                                <a class="dropdown-item" href="#"><div class="dropdown-icon"><i class="fa fa-chart-line"></i></div><span class="dropdown-content">Week</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-md">
                            <table class="table text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mois/Année</th>
                                        <th>Titre</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th>Prof.</th>
                                        <th>Date assignation</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingQuizzes as $i => $q)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $q->created_at?->format('m/Y') }}</td>
                                            <td class="fw-semibold">{{ $q->title }}</td>
                                            <td>{{ $q->module?->title }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($q->description ?? '', 80) }}</td>
                                            <td>
                                                @if(method_exists($q->module, 'teachers') && $q->module?->teachers?->count())
                                                    {{ $q->module->teachers->pluck('lastname')->join(', ') }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>{{ $q->pivot?->created_at?->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-success" wire:click="startQuiz({{ $q->id }})">
                                                    Démarrer
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Aucun quiz en attente.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- /col-xxl-9 --}}

    {{-- Colonne droite : Modules présents + Factures en attente --}}
    <div class="col-xxl-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card simplebar-scrollable-y" style="height: 416px; overflow: hidden auto;" data-simplebar="init">
                    <div class="card-header card-header-bordered">
                        <div class="card-icon text-muted"><i class="fas fa-calendar-alt fs-14 text-muted"></i></div>
                        <h3 class="card-title">Module Présent</h3>
                    </div>
                    <div class="card-body">
                        <div class="rich-list rich-list-flush">
                            <div class="flex-column align-items-stretch">
                                @forelse($presentModules as $m)
                                    <div class="rich-list-item d-flex align-items-center">
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title mb-1">{{ $m->title }}</h4>
                                            <p class="rich-list-subtitle mb-0">{{ \Illuminate\Support\Str::limit($m->description, 90) }}</p>
                                        </div>
                                        <div class="rich-list-content ms-auto text-end">
                                            <h4 class="rich-list-title mb-1">{{ $m->contents_count }} contenus</h4>
                                            <small class="text-muted">depuis {{ optional($m->pivot?->created_at)->format('d/m/Y') }}</small>
                                        </div>
                                        <div class="rich-list-append ms-2">
                                            <a href="{{ route('cand.modules.show', $m->code) }}" target="_banck" class="btn btn-sm btn-label-primary">Ouvrir</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rich-list-item">
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title mb-1">-//-</h4>
                                            <p class="rich-list-subtitle mb-0">Aucun module</p>
                                        </div>
                                        <div class="rich-list-content"><h4 class="rich-list-title mb-1">-//-</h4></div>
                                        <div class="rich-list-append">
                                            <button class="btn btn-sm btn-label-danger">aucune</button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
             <div class="col-xl-12">
                <div class="card simplebar-scrollable-y" style="height: 416px; overflow: hidden auto;" data-simplebar="init">
                    <div class="card-header card-header-bordered">
                        <div class="card-icon text-muted"><i class="fas fa-calendar-alt fs-14 text-muted"></i></div>
                        <h3 class="card-title">Facture en attente de paiement</h3>
                    </div>
                    <div class="card-body">
                        <div class="rich-list rich-list-flush">
                            <div class="flex-column align-items-stretch">
                                @forelse($pendingInvoices as $inv)
                                    <div class="rich-list-item d-flex align-items-center">
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title mb-1">{{ $inv->label ?? $inv->reference }}</h4>
                                            <p class="rich-list-subtitle mb-0">
                                                {{ number_format($inv->amount,0,',',' ') }} F CFA · {{ $inv->number }}
                                            </p>
                                        </div>
                                        <div class="rich-list-content ms-auto text-end">
                                            <h4 class="rich-list-title mb-1">{{ $inv->created_at->format('d/m/Y') }}</h4>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        </div>
                                        <div class="rich-list-append ms-2">
                                            <button class="btn btn-sm btn-primary" wire:click="payInvoice({{ $inv->id }})">
                                                Payer
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rich-list-item">
                                        <div class="rich-list-content">
                                            <h4 class="rich-list-title mb-1">-//-</h4>
                                            <p class="rich-list-subtitle mb-0">Aucune facture</p>
                                        </div>
                                        <div class="rich-list-content"><h4 class="rich-list-title mb-1">-//-</h4></div>
                                        <div class="rich-list-append">
                                            <button class="btn btn-sm btn-label-danger">aucune</button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @if(session('status'))
                            <div class="alert alert-success mt-3 mb-0">{{ session('status') }}</div>
                        @endif
                    </div>
                </div>                
            </div>
        </div>
    </div>

   
</div>
