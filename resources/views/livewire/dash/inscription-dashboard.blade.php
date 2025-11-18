<div class="row">
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-body">

                {{-- KPIs --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body d-flex">
                                <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                    <span class="avatar-title"><i class="fas fa-clipboard-list"></i></span>
                                </div>
                                <div class="ms-3">
                                    <p class="text-muted fw-medium mb-1">Total inscriptions</p>
                                    <h4 class="mb-0">{{ number_format($totalInscriptions) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body d-flex">
                                <div class="avatar-sm rounded bg-success-subtle text-success d-flex align-items-center justify-content-center">
                                    <span class="avatar-title"><i class="fas fa-check-circle"></i></span>
                                </div>
                                <div class="ms-3">
                                    <p class="text-muted fw-medium mb-1">Payées (Momo)</p>
                                    <h4 class="mb-0">{{ number_format($totalPayees) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body d-flex">
                                <div class="avatar-sm rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                                    <span class="avatar-title"><i class="fas fa-hourglass-half"></i></span>
                                </div>
                                <div class="ms-3">
                                    <p class="text-muted fw-medium mb-1">En attente</p>
                                    <h4 class="mb-0">{{ number_format($totalNonPayees) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filtres --}}
                <div class="row mt-3">
                    <div class="col-md-3 mb-2">
                        <select class="form-select" wire:model.live="paidFilter">
                            <option value="all">Toutes</option>
                            <option value="paid">Payées</option>
                            <option value="unpaid">Non payées</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" wire:model.live="perPage">
                            <option value="10">10 / page</option>
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2 d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Recherche (nom, email, téléphone, id)"
                               wire:model.debounce.500ms="search">
                        <button class="btn btn-outline-secondary" wire:click="refreshKpis">
                            <i class="fa fa-sync"></i>
                        </button>
                    </div>
                </div>

                {{-- Tableau --}}
                <div class="table-responsive mt-3">
                    <table class="table text-nowrap mb-0">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="cursor-pointer" wire:click="sortBy('id')">#</th>
                            <th>Candidat</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th class="cursor-pointer" wire:click="sortBy('montant')">Montant</th>
                            <th>Paiement</th>
                            <th>Créée le</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($inscriptions as $i)
                            @php
                                $paid  = $i->momopaiements->where('status','success')->isNotEmpty();
                                $photo = $i->user?->profile_photo_path ? route('docs.display', ['filename' => str_replace('profils/','',$i->user->profile_photo_path)]) : null;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td>{{ $i->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-circle">
                                            @if($photo)
                                                <img src="{{ $photo }}" class="avatar-2xs" alt="avatar"
                                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                <div class="avatar-2xs d-none align-items-center justify-content-center rounded-circle bg-light border">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @else
                                                <div class="avatar-2xs d-flex align-items-center justify-content-center rounded-circle bg-light border">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ ($i->user->lastname ?? '').' '.($i->user->firstname ?? '') }}</div>
                                            <div class="text-muted small">#U{{ $i->user->id ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($i->user?->email)
                                        <a href="mailto:{{ $i->user->email }}">{{ $i->user->email }}</a>
                                    @else — @endif
                                </td>
                                <td>
                                    @if($i->user?->phone)
                                        <a href="tel:{{ preg_replace('/\s+/','',$i->user->phone) }}">{{ $i->user->phone }}</a>
                                    @else — @endif
                                </td>
                                <td>{{ number_format((int)($i->montant ?? 0), 0, ',', ' ') }}</td>
                                <td>
                                    <span class="badge {{ $paid ? 'bg-success' : 'bg-warning' }}">
                                        {{ $paid ? 'Payé (Momo)' : 'En attente' }}
                                    </span>
                                </td>
                                <td>{{ optional($i->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-outline-info btn-sm" wire:click="startEdit({{ $i->id }})">
                                            <i class="far fa-edit me-1"></i> Éditer
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" wire:click="relancePaiement({{ $i->id }}, 'sms')">
                                            <i class="far fa-bell me-1"></i> Relancer
                                        </button>
                                        <button class="btn btn-label-danger btn-sm"
                                                onclick="confirm('Supprimer cette inscription ?') || event.stopImmediatePropagation()"
                                                wire:click="deleteInscription({{ $i->id }})">
                                            <i class="far fa-trash-alt me-1"></i> Suppr
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Aucune inscription trouvée.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $inscriptions->links() }}
                </div>

                {{-- Modal édition (montant uniquement) --}}
                @if($editingId)
                    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4);">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier inscription #{{ $editingId }}</h5>
                                    <button type="button" class="btn-close" wire:click="$set('editingId', null)"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Montant</label>
                                        <input type="number" class="form-control" wire:model="editingMontant" min="0">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-light" wire:click="$set('editingId', null)">Annuler</button>
                                    <button class="btn btn-primary" wire:click="saveEdit">Enregistrer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Colonne droite : Factures en attente des Users (role=candidat) --}}
    <div class="col-xxl-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <div class="card-icon text-muted"><i class="fas fa-file-invoice fs-14"></i></div>
                        <h4 class="card-title">Factures en attente</h4>
                        <div class="card-addon">
                            <button class="btn btn-label-primary py-0 btn-sm" wire:click="refreshKpis">Rafraîchir</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @php $hasAny = $unpaidByUser->isNotEmpty(); @endphp
                        @if(!$hasAny)
                            <div class="text-center text-muted py-3">Rien à afficher.</div>
                        @else
                            <div class="list-group">
                                @foreach($unpaidByUser as $userId => $items)
                                    @php
                                        $total = $items->sum('amount');
                                    @endphp
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">#User {{ $userId }}</h6>
                                            <small class="text-muted">{{ number_format((int)$total,0,',',' ') }} XAF</small>
                                        </div>
                                        <ul class="mb-0 small">
                                            @foreach($items as $inv)
                                                <li>
                                                    <span class="text-muted">{{ $inv->number }}</span> —
                                                    {{ $inv->title }} :
                                                    {{ number_format((int)$inv->amount,0,',',' ') }} XAF
                                                    @if($inv->due_date)
                                                        <em>(échéance {{ \Illuminate\Support\Carbon::parse($inv->due_date)->format('d/m/Y') }})</em>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
