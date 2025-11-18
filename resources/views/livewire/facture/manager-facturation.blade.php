<div class="container-fluid py-4" x-data @swal.window="Swal && Swal.fire($event.detail)">
    <x-slot name="title_page">Facture</x-slot>

    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-1">Gestion des Factures</h2>
            <p class="text-muted mb-0">Générez, suivez et encaissez en temps réel</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <button class="btn btn-outline-primary" wire:click="generateForCurrentOrSpecified">
                Générer (période sélectionnée)
            </button>
            <button class="btn btn-primary" wire:click="openCreate">Nouvelle facture</button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-muted small">Total factures</div>
                            <div class="bg-primary bg-opacity-10 rounded p-2">
                                <svg class="text-primary" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>
                            </div>
                        </div>
                        <div class="h3 mb-0 fw-bold">{{ $stats['count'] }}</div>
                    </div></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-muted small">Montant total</div>
                            <div class="bg-info bg-opacity-10 rounded p-2">
                                <svg class="text-info" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/></svg>
                            </div>
                        </div>
                        <div class="h3 mb-0 fw-bold">{{ number_format($stats['total'],0,',',' ') }} <small class="fs-6 text-muted">XAF</small></div>
                    </div></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-muted small">Reçu</div>
                            <div class="bg-success bg-opacity-10 rounded p-2">
                                <svg class="text-success" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/></svg>
                            </div>
                        </div>
                        <div class="h3 mb-0 fw-bold">{{ number_format($stats['received'],0,',',' ') }} <small class="fs-6 text-muted">XAF</small></div>
                    </div></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="text-muted small">En retard</div>
                            <div class="bg-warning bg-opacity-10 rounded p-2">
                                <svg class="text-warning" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>
                            </div>
                        </div>
                        <div class="h3 mb-0 fw-bold text-warning">{{ $stats['overdueCount'] }}</div>
                    </div></div>
                </div>
            </div>

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
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"><span class="visually-hidden">Toggle</span></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="submit({{ $inv->id }})">Soumettre</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="openReminders({{ $inv->id }})">Relances</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="openEdit({{ $inv->id }})">Éditer</a></li>
                                            <li><a class="dropdown-item text-warning" href="#" wire:click.prevent="cancel({{ $inv->id }})">Annuler</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="markPaid({{ $inv->id }})">Marquer payé</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="recomputeFromPayments({{ $inv->id }})">Resynchroniser</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="delete({{ $inv->id }})">Supprimer</a></li>
                                        </ul>
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

        <!-- Sidebar -->
        <aside class="col-lg-4">
            <div class="sticky-top" style="top:1rem;">
                <!-- Génération -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Génération par période</h6>
                        <div class="row g-2">
                            <div class="col-6"><input type="number" class="form-control" placeholder="Année (ex: 2025)" wire:model="gen_year"></div>
                            <div class="col-6"><input type="number" class="form-control" placeholder="Mois (1..12)" wire:model="gen_month"></div>
                            <div class="col-6"><input type="text" class="form-control" placeholder="Nature" wire:model="gen_nature"></div>
                            <div class="col-6"><input type="text" class="form-control" placeholder="Titre" wire:model="gen_title"></div>
                            <div class="col-12"><input type="number" class="form-control" placeholder="Montant (centimes XAF)" wire:model="gen_amount"></div>
                            <div class="col-12 d-grid"><button class="btn btn-outline-primary" wire:click="generateForCurrentOrSpecified">Générer</button></div>
                            <small class="text-muted">Laisse année/mois vides pour utiliser le mois en cours.</small>
                        </div>
                    </div>
                </div>

            </div>
        </aside>
    </div>

    <!-- Loader global -->
    <div wire:loading.flex class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 align-items-center justify-content-center" style="z-index:1055;">
        <div class="bg-white rounded-3 shadow-lg p-4 text-center">
            <div class="spinner-border text-primary mb-3" role="status"><span class="visually-hidden">Chargement...</span></div>
            <div class="fw-semibold">Chargement en cours...</div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    {{-- <div class="modal fade @if($showEditor) show d-block @endif" tabindex="-1" style="@if($showEditor) display:block; background-color: rgba(0,0,0,.5); @endif" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Éditer la facture' : 'Nouvelle facture' }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showEditor', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small">Candidat (user_id)</label><input type="number" class="form-control" wire:model.live="user_id"></div>
                        <div class="col-md-6"><label class="form-label small">N° facture</label><input type="text" class="form-control" wire:model.live="number" placeholder="INV-00001"></div>
                        <div class="col-md-6"><label class="form-label small">Référence</label><input type="text" class="form-control" wire:model.live="reference" placeholder="REF-00001"></div>
                        <div class="col-md-6"><label class="form-label small">Titre</label><input type="text" class="form-control" wire:model.live="title" placeholder="Frais de scolarité"></div>
                        <div class="col-md-6"><label class="form-label small">Nature</label><input type="text" class="form-control" wire:model.live="nature" placeholder="scolarite"></div>
                        <div class="col-md-6"><label class="form-label small">Montant </label><input type="number" class="form-control" wire:model.live="amount"></div>
                        <div class="col-md-6"><label class="form-label small">Devise</label><input type="text" class="form-control" wire:model.live="currency"></div>
                        <div class="col-md-6"><label class="form-label small">Émise le</label><input type="date" class="form-control" wire:model.live="issue_date"></div>
                        <div class="col-md-6"><label class="form-label small">Échéance</label><input type="date" class="form-control" wire:model.live="due_date"></div>
                        <div class="col-md-6"><label class="form-label small">Période</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="Année" wire:model.live="year">
                                <input type="number" class="form-control" placeholder="Mois" wire:model.live="month">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" wire:click="resetEditor">Réinitialiser</button>
                    <button class="btn btn-secondary" wire:click="$set('showEditor', false)">Fermer</button>
                    <button class="btn btn-primary" wire:click="saveInvoice">Enregistrer</button>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Modal Create/Edit -->
<div class="modal fade @if($showEditor) show d-block @endif" tabindex="-1"
     style="@if($showEditor) display:block; background-color: rgba(0,0,0,.5); @endif"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">{{ $editingId ? 'Éditer la facture' : 'Nouvelle facture' }}</h5>
                <button type="button" class="btn-close" wire:click="$set('showEditor', false)"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    {{-- Candidat (select) --}}
                    <div class="col-md-6">
                        <label class="form-label small">Candidat</label>
                        <select class="form-select" wire:model.live="user_id">
                            <option value="">— Sélectionner un candidat —</option>
                            @foreach($this->candidates as $c)
                                <option value="{{ $c->id }}">{{ $c->firstname.' '.$c->lastname }} — {{ $c->email }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- N° facture (auto) --}}
                    <div class="col-md-6">
                        <label class="form-label small">N° facture</label>
                        <div class="form-control bg-light text-muted">
                            @if($editingId) {{ $number }} @else Généré automatiquement @endif
                        </div>
                    </div>

                    {{-- Référence (auto) --}}
                    <div class="col-md-6">
                        <label class="form-label small">Référence</label>
                        <div class="form-control bg-light text-muted">
                            @if($editingId) {{ $reference }} @else Générée automatiquement @endif
                        </div>
                    </div>

                    {{-- Titre / Nature --}}
                    <div class="col-md-6">
                        <label class="form-label small">Titre</label>
                        <input type="text" class="form-control" wire:model.live="title" placeholder="Frais de scolarité">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Nature</label>
                        <input type="text" class="form-control" wire:model.live="nature" placeholder="scolarite">
                    </div>

                    {{-- Montant en centimes / Devise --}}
                    <div class="col-md-6">
                        <label class="form-label small">Montant </label>
                        <input type="number" class="form-control" wire:model.live="amount" min="1" step="1" placeholder="ex: 20000">
                        @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Devise</label>
                        <input type="text" class="form-control" wire:model.live="currency" placeholder="XAF">
                    </div>

                    {{-- Dates --}}
                    <div class="col-md-6">
                        <label class="form-label small">Émise le</label>
                        <input type="date" class="form-control" wire:model.live="issue_date">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Échéance</label>
                        <input type="date" class="form-control" wire:model.live="due_date">
                        {{-- astuce : on met par défaut le 15 du mois suivant côté composant --}}
                    </div>

                    {{-- Période (année/mois) --}}
                    <div class="col-md-6">
                        <label class="form-label small">Période</label>
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="Année" wire:model.live="year">
                            <input type="number" class="form-control" placeholder="Mois" wire:model.live="month">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" wire:click="resetEditor">Réinitialiser</button>
                <button class="btn btn-secondary" wire:click="$set('showEditor', false)">Fermer</button>
                <button class="btn btn-primary" wire:click="saveInvoice">Enregistrer</button>
            </div>
        </div>
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
                            <thead><tr><th>Date</th><th>Montant</th><th>Méthode</th><th>Réf</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td>{{ optional($p->received_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($p->amount,0,',',' ') }} {{ $p->invoice->currency }}</td>
                                    <td>{{ $p->method }}</td>
                                    <td>{{ $p->external_ref }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger" wire:click="removePayment({{ $p->id }})">Supprimer</button>
                                    </td>
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

    <!-- Modal Relances -->
    <div class="modal fade @if($showReminders) show d-block @endif" tabindex="-1" style="@if($showReminders) display:block; background-color: rgba(0,0,0,.5); @endif" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Relances</h5>
                    <button type="button" class="btn-close" wire:click="$set('showReminders', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="reminder_channel">
                                <option value="sms">SMS</option>
                                <option value="email">Email</option>
                                <option value="both">Les deux</option>
                            </select>
                        </div>
                        <div class="col-md-4"><input type="text" class="form-control" placeholder="Destinataire (auto si vide)" wire:model.live="reminder_to"></div>
                        <div class="col-md-5"><input type="text" class="form-control" placeholder="Message" wire:model.live="reminder_message"></div>
                        <div class="col-12 d-grid"><button class="btn btn-primary" wire:click="sendReminder({{ (int)($selectedInvoiceId ?? 0) }})">Envoyer une relance</button></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead><tr><th>Envoyée</th><th>Canal</th><th>À</th><th>Message</th></tr></thead>
                            <tbody>
                            @forelse($reminders as $r)
                                <tr>
                                    <td>{{ optional($r->sent_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ strtoupper($r->channel) }}</td>
                                    <td>{{ $r->to_contact }}</td>
                                    <td class="text-truncate" style="max-width: 420px;">{{ $r->message }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center">Aucune relance</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
