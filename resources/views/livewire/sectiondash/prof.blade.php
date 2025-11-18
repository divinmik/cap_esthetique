<div>
     <x-slot name="title_page">
        Gestion prof
    </x-slot>
    <div class="row g-4">

  <!-- Colonne principale : Liste -->
  <div class="col-lg-8">

    <!-- Stats -->
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm"><div class="card-body">
          <div class="small text-muted">Profs</div>
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
            <input type="text" class="form-control" placeholder="Nom, email, tel, code…"
                   wire:model.live="search">
          </div>


          <div class="col-md-4">
            <label class="form-label">Statut</label>
            <select class="form-select" wire:model.live="statusFilter">
              <option value="">Tous</option>
              <option value="active">Actif</option>
              <option value="blocked">Bloqué</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Table profs -->
    <div class="card shadow-sm mt-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
          <tr>
            <th>Profil</th>
            <th>Contact</th>
            <th>Programme</th>
            <th>Statut</th>
            <th class="text-end">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($users as $u)
          
            <tr @class(['table-warning'=> !($u['is_actif'] ?? true)])>
              <td>
                <div class="d-flex align-items-center gap-3">
                  @if(!empty($u['profile_photo_url'] ?? $u['avatar_path'] ?? null))
                    <img src="{{ $u['profile_photo_url'] ?? $u['avatar_path'] }}"
                         alt="avatar" class="rounded-circle" width="44" height="44"
                         style="object-fit:cover">
                  @else
                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;">
                      {{ strtoupper(Str::substr(($u['lastname'] ?? '?'),0,1)) }}
                    </div>
                  @endif
                  <div>
                    <div class="fw-semibold">
                      {{ ($u['lastname'] ?? '') }} {{ ($u['firstname'] ?? '') }}
                      <span class="text-muted">·</span>
                      <span class="text-muted small">{{ $u['code'] ?? '' }}</span>
                    </div>
                    <div class="text-muted small">{{ $u['email'] ?? '' }}</div>
                  </div>
                </div>
              </td>

              <td>
                <div>{{ $u['phone'] ?? '' }}</div>
                <div class="text-muted small">{{ $u['city'] ?? '' }}</div>
              </td>

              <td>
                <div class="small">{{ $u['program'] ?? '-' }}</div>
                @if(!empty($u['level']))
                  <span class="badge bg-primary-subtle text-primary">{{ $u['level'] }}</span>
                @endif
              </td>

              <td>
                @if(($u['is_actif'] ?? false))
                  <span class="badge bg-success">Actif</span>
                @else
                  <span class="badge bg-danger">Bloqué</span>
                @endif
              </td>

              <td class="text-end">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary"
                          title="Éditer"
                          wire:click="startEdit({{ $u['id'] }})">✏️</button>
                  <a href="{{ route('pr.manager',$u['code']) }}" target='_banck' class="btn btn-sm btn-outline-secondary">voir</a>

                  <button class="btn btn-sm btn-outline-{{ ($u['is_actif'] ?? false) ? 'warning' : 'success' }}"
                          wire:click="toggleActive({{ $u['id'] }})">
                    {{ ($u['is_actif'] ?? false) ? 'Bloquer' : 'Débloquer' }}
                  </button>

                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Plus</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      
                      <li>
                        <a class="dropdown-item" href="#" wire:click.prevent="changePassword({{ $u['id'] }})">
                          🔐 Changer mot de passe
                        </a>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <a class="dropdown-item text-danger" href="#" wire:click.prevent="delete({{ $u['id'] }})">
                          🗑️ Supprimer
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun professeur</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($users, 'links'))
        <div class="card-footer">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>

  <!-- Sidebar : Formulaire -->
  <aside class="col-lg-4">
    <div class="sticky-top" style="top:1rem;">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-3">
            {{ $action === 'edit' ? 'Modifier professeur' : 'Nouveau professeur' }}
          </h5>

          <!-- Avatar preview -->
          <div class="d-flex align-items-center gap-3 mb-3">
            @if($profile_photo_path)
              <img src="{{ $profile_photo_path->temporaryUrl() }}"
                   class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="preview">
            @elseif($profile_photo_url)
              <img src="{{ $profile_photo_url }}"
                   class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="avatar">
            @else
              <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                   style="width:64px;height:64px;">?
              </div>
            @endif

            <div class="flex-fill">
              <label class="form-label mb-1">Photo (facultatif)</label>
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
              <input type="text" class="form-control" wire:model.defer="lastname" placeholder="Ex: Mik">
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
              <button class="btn btn-success" wire:click="save">➕ Créer</button>
            @endif
          </div>
        </div>
      </div>

      <div class="alert alert-info mt-3">
        <div class="small">Astuce</div>
        <ul class="small mb-0">
          <li>Clique sur ✏️ pour précharger le formulaire d’un professeur.</li>
        </ul>
      </div>
    </div>
  </aside>

</div>

</div>
@push('scripts')
<script>
  document.addEventListener('livewire:init', () => {
    Livewire.on('copy-to-clipboard', (text) => {
      const value = String(text ?? '');
      if (!value) return;

      if (navigator.clipboard?.writeText) {
        navigator.clipboard.writeText(value)
          .then(() => Swal.fire({icon:'success', title:'Copié !', text:'Mot de passe dans le presse-papiers'}))
          .catch(() => fallbackCopy(value));
      } else {
        fallbackCopy(value);
      }
    });

    function fallbackCopy(value) {
      const el = document.createElement('textarea');
      el.value = value;
      el.setAttribute('readonly','');
      el.style.position = 'fixed';
      el.style.left = '-9999px';
      document.body.appendChild(el);
      el.select();
      try { document.execCommand('copy'); } catch (_) {}
      document.body.removeChild(el);
      Swal.fire({icon:'success', title:'Copié !'});
    }
  });
</script>

<script>
  let swalInterval = null;

  window.addEventListener('swal', (e) => {
    const payload = e?.detail ?? {};
    const first = Array.isArray(payload) ? (payload[0] ?? {}) : payload;
    
     Swal.fire({
          icon: first.icon || 'info',
          title: first.title || '',
          text: first.text || '',
          showConfirmButton: true,
          allowOutsideClick: true,
          allowEscapeKey: true,
        });
  });
</script>


@endpush


