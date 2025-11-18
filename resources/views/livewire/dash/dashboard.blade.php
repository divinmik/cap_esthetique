{{-- resources/views/livewire/dash/dashboard.blade.php --}}
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    function avatar_url($userPhoto = null, $inscriptionPhoto = null) {
        $path = $userPhoto ?: $inscriptionPhoto;
        return $path ? Storage::url($path) : null;
    }
@endphp

{{-- Style léger pour moderniser (cartes, animations) --}}
<style>
  .card-modern { border: 1px solid rgba(0,0,0,.06); border-radius: 16px; overflow: hidden; }
  .card-modern .card-header { background: linear-gradient(180deg, #ffffff, #fafafa); }
  .kpi-pill {
    display:inline-flex; align-items:center; gap:.5rem; padding:.5rem .75rem; border-radius:999px;
    background: #f8f9fa; border:1px solid rgba(0,0,0,.06);
  }
  .fade-slide-enter { opacity: 0; transform: translateY(8px); }
  .fade-slide-enter-active { transition: all .25s ease; opacity: 1; transform: translateY(0); }
  .fade-slide-leave { opacity: 1; transform: translateY(0); }
  .fade-slide-leave-active { transition: all .2s ease; opacity: 0; transform: translateY(8px); }
  .sticky-subheader { position: sticky; top: 0; z-index: 5; background: #fff; border-bottom: 1px solid rgba(0,0,0,.06); }
</style>

<div class="p-0">
  <div class="container py-3">

    {{-- ====== KPIs ====== --}}
    <div class="row g-3 mb-3">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-modern h-100">
          <div class="card-body">
            <div class="text-muted small mb-2">Professeurs inscrits (par statut)</div>
            @forelse($profByStatus as $status => $total)
              <div class="d-flex align-items-center justify-content-between py-1">
                <span class="text-capitalize">{{ $status ?: '—' }}</span>
                <span class="kpi-pill"><span class="fw-bold">{{ $total }}</span></span>
              </div>
            @empty
              <div class="text-muted">Aucun professeur</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-modern h-100">
          <div class="card-body">
            <div class="text-muted small mb-2">Candidats inscrits (par statut)</div>
            @forelse($candByStatus as $status => $total)
              <div class="d-flex align-items-center justify-content-between py-1">
                <span class="text-capitalize">{{ $status ?: '—' }}</span>
                <span class="kpi-pill"><span class="fw-bold">{{ $total }}</span></span>
              </div>
            @empty
              <div class="text-muted">Aucun candidat</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-modern h-100">
          <div class="card-body">
            <div class="text-muted small mb-2">Modules enregistrés</div>
            <div class="d-flex align-items-end justify-content-between">
              <div class="display-6 fw-bold mb-0">{{ $totalModules }}</div>
              <div class="text-muted small">CourseModule</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-3">
        <div class="card card-modern h-100">
          <div class="card-body">
            <div class="text-muted small mb-2">Inscriptions non confirmées</div>
            <div class="d-flex align-items-end justify-content-between">
              <div class="display-6 fw-bold mb-0">{{ $unconfirmedInscriptions }}</div>
              <div class="text-muted small">Sans paiement SUCCESS</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ====== ZONE TABLEAUX ====== --}}
    <div class="row g-3">

      {{-- Inscriptions en attente --}}
      <div class="col-12">
        <div class="card card-modern" style="max-height: 520px; overflow: hidden auto;" data-simplebar>
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
              <h3 class="h6 mb-0">Inscriptions en attente</h3>
              <span class="badge rounded-pill text-bg-secondary">{{ $unconfirmedInscriptionsList->total() }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <div class="d-flex align-items-center gap-1">
                <label class="small mb-0">Par page</label>
                <select class="form-select form-select-sm" wire:model.live="perPageInscriptions" style="width:auto;">
                  <option>10</option><option>25</option><option>50</option><option>100</option>
                </select>
              </div>
              <button class="btn btn-sm btn-outline-success" wire:click="exportInscriptions">
                Exporter Excel
              </button>
              <button class="btn btn-sm btn-outline-primary" wire:click="exportInscriptionsPhotos">
                Exporter photos (ZIP)
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm text-nowrap align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="cursor-pointer" wire:click="sortBy('id')">#</th>
                    <th>Code</th>
                    <th>Profil</th>
                    <th class="cursor-pointer" wire:click="sortBy('lastname')">Nom</th>
                    <th>Prénom</th>
                    <th>Date de naissance</th>
                    <th class="cursor-pointer" wire:click="sortBy('email')">Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Type formation</th>
                    <th>Niveau</th>
                    <th>Salon partenaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($unconfirmedInscriptionsList as $ins)
                    @php $photo = avatar_url(null, $ins->inscription_photo ?? null); @endphp
                    <tr class="fade-slide-enter fade-slide-enter-active">
                      <td>{{ $ins->id }}</td>
                      <td>{{ 'INS-'.str_pad($ins->id, 5, '0', STR_PAD_LEFT) }}</td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#f3f4f6;">
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
                        <div class="btn-group btn-group-sm" role="group">
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

      {{-- Modules & contenus : navigation fluide + retour --}}
      <div class="col-12 col-lg-6"
           x-data="{ mId: @entangle('moduleId') }">

        <div class="card card-modern">
          {{-- Entête modules (affiche un fil d’Ariane + retour quand un module est ouvert) --}}
          <div class="sticky-subheader px-3 py-2 d-flex align-items-center justify-content-between">
            <nav class="small" aria-label="breadcrumb">
              <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                  <a href="javascript:void(0)" @click.prevent="$wire.clearModuleSelection(); mId=null">Modules</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  <span x-show="!mId">Liste</span>
                  <span x-show="mId">Module #<span x-text="mId"></span></span>
                </li>
              </ol>
            </nav>

            <div class="d-flex align-items-center gap-2">
              <template x-if="mId">
                <button class="btn btn-sm btn-outline-secondary"
                        @click="$wire.clearModuleSelection(); mId=null">
                  ← Retour aux modules
                </button>
              </template>
            </div>
          </div>

          {{-- LISTE DES MODULES --}}
          <div class="card-body" x-show="!mId"
               x-transition:enter="fade-slide-enter"
               x-transition:enter-end="fade-slide-enter-active"
               x-transition:leave="fade-slide-leave"
               x-transition:leave-end="fade-slide-leave-active">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div>
                <h2 class="h6 mb-0">Modules & contenus</h2>
                <div class="text-muted small">Compte par module (ModuleContent)</div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center gap-1">
                  <label class="small mb-0">Par page</label>
                  <select class="form-select form-select-sm" wire:model.live="perPageModules" style="width:auto;">
                    <option>10</option><option>25</option><option>50</option><option>100</option>
                  </select>
                </div>
                <button class="btn btn-sm btn-outline-success" wire:click="exportModules">
                  Exporter Excel
                </button>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Module</th>
                    <th>Contenus</th>
                    <th>Détails types</th>
                    <th class="text-end"></th>
                  </tr>
                </thead>
                <tbody>
                @forelse($modules as $m)
                  @php
                    $count = $contentCountPerModule[$m->id] ?? 0;
                    $types = $typeBreakdown->where('course_module_id', $m->id)
                                           ->map(fn($r) => $r->type . ' (' . $r->total . ')')
                                           ->implode(', ');
                  @endphp
                  <tr class="fade-slide-enter fade-slide-enter-active">
                    <td class="fw-medium">{{ $m->title ?? ('Module #' . $m->id) }}</td>
                    <td>{{ $count }}</td>
                    <td><span class="text-muted">{{ $types ?: '—' }}</span></td>
                    <td class="text-end">
                      <button
                        class="btn btn-outline-secondary btn-sm"
                        @click="$wire.set('moduleId', {{ $m->id }}); mId={{ $m->id }};">
                        Voir contenus
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted py-4">Aucun module</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>

            <div class="mt-2">{{ $modules->links() }}</div>
          </div>

          {{-- DÉTAIL D’UN MODULE --}}
          <div class="card-body" x-show="mId"
               x-transition:enter="fade-slide-enter"
               x-transition:enter-end="fade-slide-enter-active"
               x-transition:leave="fade-slide-leave"
               x-transition:leave-end="fade-slide-leave-active">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
              <div class="d-flex align-items-center gap-1">
                <label class="small mb-0">Par page</label>
                <select class="form-select form-select-sm" wire:model.live="perPageContents" style="width:auto;">
                  <option>10</option><option>25</option><option>50</option><option>100</option>
                </select>
              </div>

              <div class="d-flex align-items-center gap-1 ms-auto">
                <label class="small mb-0">Filtrer par type</label>
                <select wire:model.live="contentTypeFilter" class="form-select form-select-sm" style="width:auto;">
                  <option value="">Tous</option>
                  @php
                    $typesForModule = $typeBreakdown->where('course_module_id', $moduleId)->pluck('type')->unique();
                  @endphp
                  @foreach($typesForModule as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                  @endforeach
                </select>
              </div>
              <button class="btn btn-sm btn-outline-success" wire:click="exportModuleContents">
                Exporter contenus (Excel)
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Titre</th>
                    <th>Fichier / Lien</th>
                    <th>Créé le</th>
                  </tr>
                </thead>
                <tbody>
                @forelse($moduleContents as $c)
                  <tr class="fade-slide-enter fade-slide-enter-active">
                    <td>{{ $c->id }}</td>
                    <td><span class="badge rounded-pill text-bg-light border">{{ $c->type }}</span></td>
                    <td>{{ $c->title ?? '—' }}</td>
                    <td class="text-truncate" style="max-width:320px;">
                      @if(!empty($c->file_path))
                        <a href="{{ Storage::url($c->file_path) }}" target="_blank" class="link-underline-primary">Fichier</a>
                      @elseif(!empty($c->url))
                        <a href="{{ $c->url }}" target="_blank" class="link-underline-primary">Lien</a>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted py-4">Aucun contenu</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>

            <div>{{ $moduleContents->links() }}</div>
          </div>
        </div>
      </div>

      {{-- Factures impayées --}}
      <div class="col-12 col-lg-6">
        <div class="card card-modern h-100">
          <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
              <h2 class="h6 mb-0">Factures en attente de paiement</h2>
              <span class="badge rounded-pill text-bg-light border">{{ $unpaidCount }}</span>
            </div>
            <div class="d-flex align-items-center gap-2" style="min-width:260px; max-width:420px; width:100%;">
              <input type="text"
                     placeholder="Rechercher (numéro, référence, nom, email)"
                     wire:model.live.debounce.400ms="searchInvoice"
                     class="form-control form-control-sm" />
              <div class="d-flex align-items-center gap-1">
                <label class="small mb-0">Par page</label>
                <select class="form-select form-select-sm" wire:model.live="perPageInvoices" style="width:auto;">
                  <option>10</option><option>25</option><option>50</option><option>100</option>
                </select>
              </div>
              <button class="btn btn-sm btn-outline-success" wire:click="exportInvoices">Exporter Excel</button>
              <button class="btn btn-sm btn-outline-primary" wire:click="exportInvoiceUserPhotos">Photos (ZIP)</button>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>#</th>
                    <th>Référence</th>
                    <th>Candidat</th>
                    <th>Montant</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                @forelse($unpaidInvoices as $inv)
                  <tr class="fade-slide-enter fade-slide-enter-active">
                    <td>{{ optional($inv->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="fw-medium">{{ $inv->number ?? $inv->id }}</td>
                    <td>{{ $inv->reference ?? '—' }}</td>
                    <td>
                      <div class="fw-medium">{{ trim(($inv->user_firstname ?? '').' '.($inv->user_lastname ?? '')) ?: $inv->candidate_name }}</div>
                      <div class="text-muted small">{{ $inv->candidate_email }}</div>
                    </td>
                    <td>{{ number_format($inv->amount ?? 0, 0, ',', ' ') }} {{ $inv->currency ?? 'XAF' }}</td>
                    <td><span class="badge rounded-pill text-bg-light border">{{ $inv->status }}</span></td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center text-muted py-4">Aucune facture impayée</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>
            <div class="mt-2">{{ $unpaidInvoices->links() }}</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

