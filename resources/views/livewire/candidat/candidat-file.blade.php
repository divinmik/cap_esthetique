{{-- resources/views/livewire/candidates/show.blade.php --}}
@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <x-slot name="title_page">
        Profil {{ $candidate->lastname }} {{ $candidate->firstname }}
    </x-slot>

    <div class="row">
        <!-- COLONNE GAUCHE : PROFIL -->
        <div class="col-xl-3">
            <div class="card overflow-hidden">
                <div class="bg-primary-subtle">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="text-primary p-3 mb-3">
                                <h5 class="text-primary">CAP</h5>
                                <p class="mb-0">Espace candidat</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <img src="/admin/assets/images/contact.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="row align-items-end">
                        <div class="col-sm-4">
                            <div class="avatar-md mb-3 mt-n4">
                                @if($candidate->profile_photo_path)
                                    <img src="{{ Storage::url($candidate->profile_photo_path) }}"
                                         class="img-fluid avatar-circle bg-light p-2 border-2 border-primary" alt="photo">
                                @else
                                    <img src="/admin/assets/images/placeholder-user.png"
                                         class="img-fluid avatar-circle bg-light p-2 border-2 border-primary" alt="photo">
                                @endif
                            </div>
                            <h5 class="fs-16 mb-1 text-truncate">
                                {{ $candidate->firstname }} {{ $candidate->lastname }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body border-top">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-borderless mb-0">
                            <tbody>
                            <tr>
                                <th><i class="mdi mdi-account text-primary me-2"></i> Nom :</th>
                                <td>{{ $candidate->lastname }} {{ $candidate->firstname }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-cellphone text-primary me-2"></i> Téléphone :</th>
                                <td>{{ $candidate->phone }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-calendar-account-outline text-primary me-2"></i> Naissance :</th>
                                <td>{{ optional($candidate->birthdate)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-google-maps text-primary me-2"></i> Lieu :</th>
                                <td>{{ $candidate->birthplace }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-email text-primary me-2"></i> E-mail :</th>
                                <td>{{ $candidate->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-pound text-primary me-2"></i> Code :</th>
                                <td class="fw-bold">{{ $candidate->code ?? '—' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- COLONNE DROITE -->
        <div class="col-xl-9">

            <div class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <div class="card"><div class="card-body d-flex">
                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                            <span class="avatar-title"><i class="mdi mdi-school"></i></span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted fw-medium mb-1">Modules</p>
                            <h4 class="mb-0">{{ $stats['modules'] }}</h4>
                        </div>
                    </div></div>
                </div>
                <div class="col-md-4">
                    <div class="card"><div class="card-body d-flex">
                        <div class="avatar-sm rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                            <span class="avatar-title"><i class="mdi mdi-receipt"></i></span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted fw-medium mb-1">Factures en attente</p>
                            <h4 class="mb-0">{{ $stats['invoices_pending'] }}</h4>
                        </div>
                    </div></div>
                </div>
                <div class="col-md-4">
                    <div class="card"><div class="card-body d-flex">
                        <div class="avatar-sm rounded bg-success-subtle text-success d-flex align-items-center justify-content-center">
                            <span class="avatar-title"><i class="mdi mdi-check-decagram"></i></span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted fw-medium mb-1">Quiz en attente</p>
                            <h4 class="mb-0">{{ $stats['quizzes_pending'] }}</h4>
                        </div>
                    </div></div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success mb-3">{{ session('status') }}</div>
                    @endif

                    <div class="mb-3">
                        <div class="nav nav-lines" role="tablist">
                            <a class="nav-item nav-link {{ $tab==='invoices'?'active':'' }}" href="#"
                               wire:click.prevent="setTab('invoices')">Factures</a>
                            <a class="nav-item nav-link {{ $tab==='modules'?'active':'' }}" href="#"
                               wire:click.prevent="setTab('modules')">Modules</a>
                            <a class="nav-item nav-link {{ $tab==='quizzes'?'active':'' }}" href="#"
                               wire:click.prevent="setTab('quizzes')">Quiz</a>
                            <a class="nav-item nav-link {{ $tab==='profile'?'active':'' }}" href="#"
                               wire:click.prevent="setTab('profile')">Profil</a>
                            <a class="nav-item nav-link {{ $tab==='password'?'active':'' }}" href="#"
                               wire:click.prevent="setTab('password')">Mot de passe</a>
                        </div>
                    </div>

                    <div class="tab-content">

                        {{-- ================= TAB FACTURES ================= --}}
                        @if($tab==='invoices')
                        <div class="tab-pane fade active show">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                <h5 class="mb-0">Factures du candidat</h5>
                                <div class="btn-group" role="group" aria-label="Filter">
                                    <button class="btn btn-outline-secondary {{ $invoiceFilter==='all'?'active':'' }}"
                                            wire:click="setInvoiceFilter('all')">Toutes</button>
                                    <button class="btn btn-outline-warning {{ $invoiceFilter==='unpaid'?'active':'' }}"
                                            wire:click="setInvoiceFilter('unpaid')">En attente</button>
                                    <button class="btn btn-outline-success {{ $invoiceFilter==='paid'?'active':'' }}"
                                            wire:click="setInvoiceFilter('paid')">Payées</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>N°</th>
                                        <th>Référence</th>
                                        <th>Objet</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Créée le</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($invoices as $k => $inv)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td class="fw-semibold">{{ $inv->number }}</td>
                                            <td>{{ $inv->reference }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($inv->label, 80) }}</td>
                                            <td>{{ number_format($inv->amount,0,',',' ') }} F CFA</td>
                                            <td>
                                                @if($inv->status==='paid')
                                                    <span class="badge bg-success">Payée</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                @endif
                                            </td>
                                            <td>{{ $inv->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                @if($inv->status==='unpaid')
                                                    <button class="btn btn-sm btn-primary"
                                                            wire:click="payInvoice({{ $inv->id }})">
                                                        <i class="mdi mdi-credit-card-outline"></i> Payer
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                                        <i class="mdi mdi-check"></i> Réglée
                                                    </button>
                                                @endif
                                                @if($inv->pdf_path ?? false)
                                                    <a class="btn btn-sm btn-outline-info ms-1"
                                                       href="{{ Storage::url($inv->pdf_path) }}" target="_blank">
                                                        <i class="mdi mdi-file-pdf-box"></i> PDF
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">Aucune facture.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- ================= TAB MODULES ================= --}}
                        @if($tab==='modules')
                        <div class="tab-pane fade active show">
                            <h5 class="mb-3">Modules possible</h5>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Prof.</th>
                                        <th>Contenus</th>
                                        <th>Date d’inscription</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($modules as $i => $m)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td class="fw-semibold">{{ $m->title }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($m->description, 90) }}</td>
                                            <td>{{ $m->user->firstname." ".$m->user->lastname }}</td>
                                            <td>{{ $m->contents_count }}</td>
                                            <td>{{ optional($m->pivot?->created_at)->format('d/m/Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('cand.modules.show', $m->code) }}" target="_banck" class="btn btn-sm btn-outline-primary">
                                                    Ouvrir
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted">Aucun module inscrit.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- ================= TAB QUIZZES ================= --}}
                        @if($tab==='quizzes')
                        <div class="tab-pane fade active show">
                            <h5 class="mb-3">Quiz en attente</h5>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Quiz</th>
                                        <th>Module</th>
                                        <th>Statut</th>
                                        <th>Assigné le</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($pendingQuizzes as $i => $q)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td class="fw-semibold">{{ $q->title }}</td>
                                            <td>{{ $q->module?->title }}</td>
                                            <td><span class="badge bg-warning text-dark">En attente</span></td>
                                            <td>{{ optional($q->pivot?->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('quizzes.take', [$q->id, 'candidate' => $candidate->id]) }}"
                                                   class="btn btn-sm btn-success">
                                                    Démarrer
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted">Aucun quiz en attente.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        {{-- ================= TAB PROFIL ================= --}}
                        @if($tab==='profile')
                        <div class="tab-pane fade active show">
                            <h5 class="mb-3">Mise à jour du profil</h5>
                            <form wire:submit.prevent="saveProfile" class="row g-3" enctype="multipart/form-data">
                                <div class="col-md-3">
                                    <label class="form-label">Nom *</label>
                                    <input type="text" class="form-control" wire:model.defer="lastname">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" wire:model.defer="firstname">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">E-mail *</label>
                                    <input type="email" class="form-control" wire:model.defer="email">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" wire:model.defer="phone">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" wire:model.defer="birthdate">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Lieu de naissance</label>
                                    <input type="text" class="form-control" wire:model.defer="birthplace">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Photo</label>
                                    <input type="file" class="form-control" wire:model.live="profile_photo" accept="image/*">
                                    <div wire:loading wire:target="profile_photo" class="form-text">Téléversement...</div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                        @endif

                        {{-- ================= TAB MOT DE PASSE ================= --}}
                        @if($tab==='password')
                        <div class="tab-pane fade active show">
                            <h5 class="mb-3">Changement du mot de passe</h5>
                            <form wire:submit.prevent="savePassword" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Ancien mot de passe *</label>
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                           wire:model.defer="old_password">
                                    @error('old_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nouveau mot de passe *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           wire:model.defer="password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">Minimum 8 caractères.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirmer *</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                           wire:model.defer="password_confirmation">
                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
