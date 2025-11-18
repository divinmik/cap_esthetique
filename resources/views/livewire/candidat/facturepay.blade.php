<div class="container-fluid py-4" x-data @swal.window="Swal && Swal.fire($event.detail)">
    <x-slot name="title_page">
        Facture
    </x-slot>

  <!-- Header avec titre -->
  <div class="row mb-4">
    <div class="col">
      <h2 class="fw-bold mb-1">Gestion des Factures</h2>
      <p class="text-muted mb-0">Suivez et gérez vos factures en temps réel</p>
    </div>
  </div>

  <div class="row g-4">
    <!-- Colonne principale -->
    <div class="col-lg-8">
      <!-- Stats Cards avec icônes et couleurs -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="text-muted small">Total factures</div>
                <div class="bg-primary bg-opacity-10 rounded p-2">
                  <svg class="text-primary" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                  </svg>
                </div>
              </div>
              <div class="h3 mb-0 fw-bold">{{ $stats['count'] }}</div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="text-muted small">Montant total</div>
                <div class="bg-info bg-opacity-10 rounded p-2">
                  <svg class="text-info" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                  </svg>
                </div>
              </div>
              <div class="h3 mb-0 fw-bold">{{ number_format($stats['total']/100,0,',',' ') }} <small class="fs-6 text-muted">XAF</small></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="text-muted small">Reçu</div>
                <div class="bg-success bg-opacity-10 rounded p-2">
                  <svg class="text-success" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                  </svg>
                </div>
              </div>
              <div class="h3 mb-0 fw-bold">{{ number_format($stats['received']/100,0,',',' ') }} <small class="fs-6 text-muted">XAF</small></div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="text-muted small">En retard</div>
                <div class="bg-warning bg-opacity-10 rounded p-2">
                  <svg class="text-warning" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                  </svg>
                </div>
              </div>
              <div class="h3 mb-0 fw-bold text-warning">{{ $stats['overdueCount'] }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtres avec design moderne -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
          <h5 class="mb-0 fw-semibold">Filtres de recherche</h5>
        </div>
        <div class="card-body pt-2">
          <div class="row g-3">
            
            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Statut</label>
              <select class="form-select" wire:model="status">
                <option value="">Tous les statuts</option>
                <option value="unpaid">Impayé</option>
                <option value="partial">Partiel</option>
                <option value="paid">Payé</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label small text-muted mb-1">Du</label>
              <input type="date" class="form-control" wire:model="from">
            </div>
            <div class="col-md-2">
              <label class="form-label small text-muted mb-1">Au</label>
              <input type="date" class="form-control" wire:model="to">
            </div>
          </div>
        </div>
      </div>

      <!-- Table améliorée -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-0">
          <h5 class="mb-0 fw-semibold">Liste des factures</h5>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="border-bottom">
              <tr>
                <th class="text-muted fw-semibold small">#</th>
                <th class="text-muted fw-semibold small">CANDIDAT</th>
                <th class="text-muted fw-semibold small">MONTANT</th>
                <th class="text-muted fw-semibold small">PAYÉ</th>
                <th class="text-muted fw-semibold small">STATUT</th>
                <th class="text-muted fw-semibold small">ÉCHÉANCE</th>
                <th class="text-muted fw-semibold small text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($invoices as $inv)
                @php
                  $balance = max(0, $inv['amount'] - $inv['amount_paid']);
                  $overdue = ($inv['status'] !== 'paid') && !empty($inv['due_date']) && now()->gt($inv['due_date']);
                @endphp
                <tr @class(['bg-warning bg-opacity-10'=> $overdue])>
                  <td>
                    <div class="fw-bold text-primary">{{ $inv['number'] }}</div>
                    <div class="text-muted small">{{ $inv['reference'] }}</div>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <span class="fw-bold text-primary">{{ substr($inv['candidate']['name'], 0, 1) }}</span>
                      </div>
                      <div>
                        <div class="fw-semibold">{{ $inv['candidate']['name'] }}</div>
                        <div class="text-muted small">{{ $inv['candidate']['email'] }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="fw-semibold">{{ number_format($inv['amount']/100,0,',',' ') }} <span class="text-muted small">{{ $inv['currency'] }}</span></td>
                  <td>
                    <span class="fw-semibold text-success">{{ number_format($inv['amount_paid']/100,0,',',' ') }}</span> <span class="text-muted small">{{ $inv['currency'] }}</span>
                  </td>
                  <td>
                    <div class="d-flex gap-1 flex-wrap">
                      @if($inv['status']==='paid')
                        <span class="badge rounded-pill bg-success">Payé</span>
                      @elseif($inv['status']==='partial')
                        <span class="badge rounded-pill bg-info">Partiel</span>
                      @else
                        <span class="badge rounded-pill bg-danger">Impayé</span>
                      @endif
                      @if($overdue)
                        <span class="badge rounded-pill bg-warning text-dark">En retard</span>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div class="fw-semibold">{{ \Illuminate\Support\Carbon::parse($inv['due_date'])->format('d/m/Y') }}</div>
                    <small class="text-muted">Émise: {{ \Illuminate\Support\Carbon::parse($inv['issue_date'])->format('d/m/Y') }}</small>
                  </td>
                  <td class="text-end">
                    <div class="btn-group" role="group">
                        @if($inv['status']!=='paid')
                            <button class="btn btn-sm btn-success" wire:click="markPaid({{ $inv['id'] }})">
                                <svg width="14" height="14" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                </svg>
                                Payé
                            </button>
                        @else
                            Paiement validé
                        @endif                      
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <svg width="48" height="48" fill="currentColor" class="text-muted mb-3" viewBox="0 0 16 16">
                      <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                    </svg>
                    <div class="text-muted">Aucune facture trouvée</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sidebar moderne -->
    <aside class="col-lg-4">
      <div class="sticky-top" style="top:1rem;">
        <!-- Légende -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <h6 class="fw-bold mb-3">
              <svg width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg>
              Légende des statuts
            </h6>
            <div class="d-flex flex-column gap-2">
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-success me-2" style="width: 60px;">Payé</span>
                <small class="text-muted">Montant entièrement réglé</small>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-info me-2" style="width: 60px;">Partiel</span>
                <small class="text-muted">Acompte reçu</small>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-danger me-2" style="width: 60px;">Impayé</span>
                <small class="text-muted">Aucun paiement effectué</small>
              </div>
              <div class="d-flex align-items-center">
                <span class="badge rounded-pill bg-warning text-dark me-2" style="width: 60px;">Retard</span>
                <small class="text-muted">Échéance dépassée</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Info système -->
        <div class="card border-0 shadow-sm bg-light">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <svg width="20" height="20" fill="currentColor" class="text-primary me-2 mt-1 flex-shrink-0" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
              </svg>
              <small class="text-muted">Données factices stockées en mémoire. Aucune connexion à une base de données réelle.</small>
            </div>
          </div>
        </div>
      </div>
    </aside>
  </div>

  <!-- Loader amélioré -->
  <div wire:loading.flex class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 align-items-center justify-content-center" style="z-index:1055;">
    <div class="bg-white rounded-3 shadow-lg p-4 text-center">
      <div class="spinner-border text-primary mb-3" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <div class="fw-semibold">Chargement en cours...</div>
    </div>
  </div>
</div>