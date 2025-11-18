<div>
  <x-slot name="title_page">
    Gestion candidats
  </x-slot>

  <div class="row g-4">

    <!-- Colonne principale : Liste -->

    <div class="col-lg-8">
      
      <!-- Stats -->
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Candidats</div>
            <div class="h4 mb-0">{{ $stats['count'] ?? 0 }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Actifs</div>
            <div class="h4 mb-0">{{ $stats['active'] ?? 0 }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Bloqués</div>
            <div class="h4 mb-0">{{ $stats['blocked'] ?? 0 }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Avec photo</div>
            <div class="h4 mb-0">{{ $stats['with_photo'] ?? 0 }}</div>
          </div></div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="card shadow-sm mt-3">
        <div class="card-body">
          <div class="row justify-content-center g-2 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Recherche</label>
              <input type="text" class="form-control" placeholder="Nom, email, tel…"
                     wire:model.live="search">
            </div>

            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select class="form-select" wire:model.live="statusFilter">
                <option value="">Tous</option>
                <option value="1">Actif</option>
                <option value="0">Bloqué</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Table candidats -->
      <div class="card shadow-sm mt-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
              <th>Profil</th>
              <th>Contact</th>
              <th>Programme / Niveau</th>
              <th>Factures</th>
              <th>Adresse</th>
              <th>Etat</th>
              <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($candidates as $c)
              @php $isActif = $c['is_actif']; @endphp
              
              <tr @class(['table-danger'=> ! $isActif])>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    @if(!empty($c['profile_photo_url'] ?? $c['profile_photo_path'] ?? null))
                      <img src="{{ $c['profile_photo_url'] ?? (Storage::disk('public')->url($c['profile_photo_path'])) }}"
                           alt="avatar" class="rounded-circle" width="44" height="44"
                           style="object-fit:cover">
                    @else
                      <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                           style="width:44px;height:44px;">
                        {{ strtoupper(Str::substr(($c['lastname'] ?? '?'),0,1)) }}
                      </div>
                    @endif
                    <div>
                      <div class="fw-semibold">
                        
                        {{ ($c['lastname'] ?? '') }} {{ ($c['firstname'] ?? '') }}
                      </div>
                      <div class="text-muted small">
                        Née: {{ $c['birthdate'] ?? '' }} <br>
                        lieu: {{ $c['birthplace'] ?? '' }}
                      </div>
                    </div>
                  </div>
                </td>

                <td>
                  <div>{{ $c['phone'] ?? '' }}</div>
                  <div class="text-muted small">{{ $c['email'] ?? '' }}</div>
                  <div class="text-muted small">{{ $c['city'] ?? '' }}</div>
                </td>

                <td>
                  <div class="small">{{ $c['program'] ?? '-' }}</div>
                  @if(!empty($c['level']))
                    <span class="badge bg-primary-subtle text-primary">{{ $c['level'] }}</span>
                    @if(($c['level'] ?? '') === 'Autre' && !empty($c['level_other']))
                      <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $c['level_other'] }}</span>
                    @endif
                  @endif
                </td>

                <td>
                  @php
                    $unpaid = $c['unpaid_invoices'] ?? []; // idéal: fourni par le composant pour éviter N+1
                  @endphp

                  @if(!empty($unpaid))
                    <div class="small">
                      @foreach($unpaid as $inv)
                        <div class="mb-1">
                          <span class="badge text-bg-danger">{{ $inv['number'] ?? '' }}</span>
                          {{ number_format($inv['amount'] ?? 0, 0, ' ', ' ') }} FCFA
                          @if(!empty($inv['due_date'])) 
                            <span class="text-muted"> (échéance {{ \Carbon\Carbon::parse($inv['due_date'])->format('d/m/Y') }})</span>
                          @endif
                          <button class="btn btn-sm btn-outline-danger ms-1"
                                  wire:click="sendReminder({{ $c['id'] }}, 'invoice', {{ $inv['id'] ?? 'null' }}, 'both')">
                            Relancer
                          </button>
                          
                        </div>
                      @endforeach
                    </div>
                  @else
                    <span class="badge text-bg-success">À jour</span>
                  @endif
                </td>

                <td>
                  {{ $c['address']  }}
                    
                </td>
                <td>
                  @switch($c['is_actif'])
                    @case('1')  <span class="badge bg-success">Actif</span> @break
                    @case('0') <span class="badge bg-danger">Bloqué</span> @break
                    @default         <span class="badge bg-warning text-dark">En attente</span>
                  @endswitch
                </td>

                <td class="text-end">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary"
                            title="Éditer"
                            wire:click="startEdit({{ $c['id'] }})">✏️</button>
                    <a href="{{ route('cand.filecand',$c['code']) }}" target='_banck' class="btn btn-sm btn-outline-secondary">voir</a>
                   

                    <button class="btn btn-sm btn-outline-{{ $isActif==1 ? 'danger' : 'success' }}"
                            wire:click="toggleActive({{ $c['id'] }})">
                      {{ $isActif==1 ? 'Bloquer' : 'Débloquer' }}
                    </button>

                    <div class="btn-group">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Plus</button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <a class="dropdown-item text-danger" href="#" wire:click.prevent="delete({{ $c['id'] }})">
                            🗑️ Supprimer
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">Aucun candidat</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>

        {{-- @if(method_exists($candidates, 'links'))
          <div class="card-footer">
            {{ $candidates->links() }}
          </div>
        @endif --}}
      </div>

    </div>

    <!-- Sidebar : Formulaire -->
    <aside class="col-lg-4">
      <div class="sticky-top" style="top:1rem;">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">
              {{ $action === 'edit' ? 'Modifier candidat' : 'Nouveau candidat' }}
            </h5>

            <!-- Avatar preview -->
            <div class="d-flex align-items-center gap-3 mb-3">
              @if($profile_photo_path)
                <img src="{{ $profile_photo_path->temporaryUrl() }}"
                     class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="preview">
              @elseif(!empty($profile_photo_url))
                <img src="{{ $profile_photo_url }}"
                     class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="avatar">
              @else
                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                     style="width:64px;height:64px;">?
                </div>
              @endif

              <div class="flex-fill">
                <label class="form-label mb-1">Photo (max 5 Mo)</label>
                <input type="file" class="form-control" wire:model="profile_photo_path" accept="image/*">
                @error('profile_photo_path') <div class="small text-danger">{{ $message }}</div> @enderror
                <div wire:loading wire:target="profile_photo_path" class="small text-muted mt-1">Chargement photo…</div>
              </div>
            </div>

            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" wire:model.defer="firstname" placeholder="Ex: Divin">
                @error('firstname') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" wire:model.defer="lastname" placeholder="Ex: Nsimba">
                @error('lastname') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row g-2 mt-1">
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" wire:model.defer="email" placeholder="exemple@mail.com">
                @error('email') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" wire:model.defer="phone" placeholder="06XXXXXXXX">
                @error('phone') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row g-2 mt-1">
              <div class="col-md-6">
                <label class="form-label">Date de naissance</label>
                <input type="date" class="form-control" wire:model.defer="birthdate">
                @error('birthdate') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Lieu de naissance</label>
                <input type="text" class="form-control" wire:model.defer="birthplace" placeholder="Ville, pays">
                @error('birthplace') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row g-2 mt-1">
              <div class="col-md-6">
                <label class="form-label">Ville</label>
                <input type="text" class="form-control" wire:model.defer="city">
                @error('city') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Programme</label>
                <input type="text" class="form-control" wire:model.defer="program">
                @error('program') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="row g-2 mt-1">
              <div class="col-md-6">
                <label class="form-label">Niveau</label>
                <select class="form-select" wire:model.defer="level">
                  <option value="">Choisir…</option>
                  <option value="CEP">CEP</option>
                  <option value="BEPC">BEPC</option>
                  <option value="BAC">BAC</option>
                  <option value="Autre">Autre</option>
                </select>
                @error('level') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Autre niveau</label>
                <input type="text" class="form-control" wire:model.defer="level_other">
                @error('level_other') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Adresse</label>
               <textarea id="message" wire:model="address" rows="4" class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2" placeholder="Votre adresse..."></textarea>
                @error('address') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            </div>

            @if($action === 'edit')
              <div class="mt-2">
                <label class="form-label">Statut du compte</label>
                <select class="form-select" wire:model.defer="is_actif">
                  <option value="1">Actif</option>
                  <option value="0">Bloqué</option>
                </select>
                @error('is_actif') <div class="small text-danger">{{ $message }}</div> @enderror
              </div>
            @endif

            <div class="d-grid gap-2 mt-3">
              @if($action === 'edit')
                <button class="btn btn-primary" wire:click="edit">💾 Mettre à jour</button>
              @else
               {{--  <button class="btn btn-success" wire:click="save">➕ Créer</button> --}}
              @endif
             {{--  <button class="btn btn-outline-info" type="button"
                      @if(isset($currentId)) wire:click="sendReminder({{ $currentId }}, 'registration', null, 'both')" @endif>
                🔔 Relancer inscription
              </button> --}}
            </div>
          </div>
        </div>

        <div class="alert alert-info mt-3">
          <div class="small">Astuce</div>
          <ul class="small mb-0">
            <li>Clique sur ✏️ pour précharger le formulaire d’un candidat.</li>
          </ul>
        </div>
      </div>
    </aside>

  </div>
</div>
