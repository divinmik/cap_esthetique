<div class="container-fluid py-4" x-data @swal.window="Swal && Swal.fire($event.detail)">
    <x-slot name="title_page">Factures</x-slot>

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-lg-12">
            <!-- Filtres -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3"><h5 class="mb-0 fw-semibold">Filtres de recherche</h5></div>
                <div class="card-body pt-2">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small text-muted mb-1">Recherche</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="N° facture, nom, email..." wire:model.live="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Statut</label>
                            <select class="form-select" wire:model.live="status">
                                <option value="">Tous</option>
                                <option value="unpaid">Impayé</option>
                                <option value="partial">Partiel</option>
                                <option value="paid">Payé</option>
                                <option value="cancelled">Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">Du</label>
                            <input type="date" class="form-control" wire:model.live="from">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted mb-1">Au</label>
                            <input type="date" class="form-control" wire:model.live="to">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-0"><h5 class="mb-0 fw-semibold">Liste des factures</h5></div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="border-bottom">
                        <tr>
                            <th class="text-muted fw-semibold small">#</th>
                            <th class="text-muted fw-semibold small">CANDIDAT</th>
                            <th class="text-muted fw-semibold small">MONTANT</th>
                            <th class="text-muted fw-semibold small">MOIS/ANNEE</th>
                            <th class="text-muted fw-semibold small">PAYÉ</th>
                            <th class="text-muted fw-semibold small">STATUT</th>
                            <th class="text-muted fw-semibold small">ÉCHÉANCE</th>
                            <th class="text-muted fw-semibold small text-end">ACTIONS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $inv)
                            @php
                                $balance = max(0, $inv->amount - $inv->amount_paid);
                                $overdue = ($inv->status !== 'paid') && $inv->due_date && now()->gt($inv->due_date);
                            @endphp
                            <tr @class(['bg-warning bg-opacity-10'=> $overdue])>
                                <td>
                                    <div class="fw-bold text-primary">{{ $inv->number }}</div>
                                    <div class="text-muted small">{{ $inv->reference }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                            <span class="fw-bold text-primary">{{ strtoupper(substr($inv->user->firstname,0,1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $inv->user->lastname." ".$inv->user->firstname }}</div>
                                            <div class="text-muted small">{{ $inv->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold">{{ number_format($inv->amount,0,',',' ') }} <span class="text-muted small">{{ $inv->currency }}</span></td>
                                <td><span class="fw-semibold">{{ $inv->month.'-'.$inv->year }}</td>
                                <td><span class="fw-semibold text-success">{{ number_format($inv->amount_paid,0,',',' ') }}</span> <span class="text-muted small">{{ $inv->currency }}</span></td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($inv->status==='paid')
                                            <span class="badge rounded-pill bg-success">Payé</span>
                                        @elseif($inv->status==='partial')
                                            <span class="badge rounded-pill bg-info">Partiel</span>
                                        @elseif($inv->status==='cancelled')
                                            <span class="badge rounded-pill bg-secondary">Annulée</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger">Impayé</span>
                                        @endif
                                        @if($overdue)
                                            <span class="badge rounded-pill bg-warning text-dark">En retard</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ optional($inv->due_date)->format('d/m/Y') }}</div>
                                    <small class="text-muted">Émise: {{ optional($inv->created_at)->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-success" wire:click="openPayments({{ $inv->id }})">Paiements</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <svg width="48" height="48" fill="currentColor" class="text-muted mb-3" viewBox="0 0 16 16"><path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>
                                    <div class="text-muted">Aucune facture trouvée</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Loader global -->
    <div wire:loading.flex class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 align-items-center justify-content-center" style="z-index:1055;">
        <div class="bg-white rounded-3 shadow-lg p-4 text-center">
            <div class="spinner-border text-primary mb-3" role="status"><span class="visually-hidden">Chargement...</span></div>
            <div class="fw-semibold">Chargement en cours...</div>
        </div>
    </div>

        <!-- Modal Paiements -->
    <div class="modal fade @if($showPayments) show d-block @endif" tabindex="-1" style="@if($showPayments) display:block; background-color: rgba(0,0,0,.5); @endif" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Avances & Paiements</h5>
                    <button type="button" class="btn-close" wire:click="$set('showPayments', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><input type="number" class="form-control" placeholder="Montant " wire:model.live="pay_amount"></div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="Méthode (cash/momo)" wire:model.live="pay_method"></div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="Référence externe" wire:model.live="pay_ref"></div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-success" wire:click="addPayment({{ (int)($selectedInvoiceId ?? 0) }})">Ajouter une avance</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead><tr><th>Date</th><th>Montant</th><th>Méthode</th><th>Réf</th></tr></thead>
                            <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td>{{ optional($p->received_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($p->amount,0,',',' ') }} {{ $p->invoice->currency }}</td>
                                    <td>{{ $p->method }}</td>
                                    <td>{{ $p->external_ref }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-muted text-center">Aucun paiement</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
