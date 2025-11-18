<div>
     <x-slot name="title_page">
       Utilisateur
    </x-slot>
    <div class="container my-4" x-data @swal.window="Swal && Swal.fire($event.detail)">

  <div class="row g-4">

    <!-- Colonne principale : Liste -->
    <div class="col-lg-8">
      <!-- Stats -->
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Utilisateurs</div>
            <div class="h4 mb-0">{{ $stats['count'] }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Actifs</div>
            <div class="h4 mb-0">{{ $stats['active'] }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Bloqués</div>
            <div class="h4 mb-0">{{ $stats['blocked'] }}</div>
          </div></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card shadow-sm"><div class="card-body">
            <div class="small text-muted">Admins</div>
            <div class="h4 mb-0">{{ $stats['admins'] }}</div>
          </div></div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="card shadow-sm mt-3">
        <div class="card-body">
          <div class="row g-2 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Recherche</label>
              <input type="text" class="form-control" placeholder="Nom, email, tel, rôle..."
                     wire:model.debounce.400ms="search">
            </div>
            <div class="col-md-4">
              <label class="form-label">Rôle</label>
              <select class="form-select" wire:model="roleFilter">
                <option value="">Tous</option>
                @foreach($this->roles as $r)
                  <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select class="form-select" wire:model="statusFilter">
                <option value="">Tous</option>
                <option value="active">Actif</option>
                <option value="blocked">Bloqué</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Table utilisateurs -->
      <div class="card shadow-sm mt-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
              <th>Profil</th>
              <th>Contact</th>
              <th>Rôle</th>
              <th>Statut</th>
              <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
              <tr @class(['table-warning'=> $u['status']==='blocked'])>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    @if($u['avatar_path'])
                      <img src="{{ $u['avatar_path'] }}" alt="avatar" class="rounded-circle" width="44" height="44">
                    @else
                      <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                           style="width:44px;height:44px;">
                        {{ strtoupper(Str::substr($u['name'],0,1)) }}
                      </div>
                    @endif
                    <div>
                      <div class="fw-semibold">{{ $u['name'] }}</div>
                      <div class="text-muted small">{{ $u['email'] }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <div>{{ $u['phone'] }}</div>
                </td>
                <td>
                  <span class="badge bg-primary-subtle text-primary">{{ ucfirst($u['role']) }}</span>
                </td>
                <td>
                  @if($u['status']==='active')
                    <span class="badge bg-success">Actif</span>
                  @else
                    <span class="badge bg-danger">Bloqué</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary" wire:click="startEdit({{ $u['id'] }})">✏️</button>
                    <button class="btn btn-sm btn-outline-{{ $u['status']==='active' ? 'warning' : 'success' }}"
                            wire:click="toggleBlock({{ $u['id'] }})">
                      {{ $u['status']==='active' ? 'Bloquer' : 'Débloquer' }}
                    </button>
                    <div class="btn-group">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Plus</button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <a class="dropdown-item" href="#" wire:click.prevent="copyPassword({{ $u['id'] }})">
                            📋 Copier mot de passe
                          </a>
                        </li>
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
              <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Sidebar : Formulaire -->
    <aside class="col-lg-4">
      <div class="sticky-top" style="top:1rem;">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">
              {{ $editingId ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}
            </h5>

            <!-- Avatar preview -->
            <div class="d-flex align-items-center gap-3 mb-3">
              @if($photo)
                <img src="{{ $photo->temporaryUrl() }}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="preview">
              @elseif($avatar_path)
                <img src="{{ $avatar_path }}" class="rounded-circle" style="width:64px;height:64px;object-fit:cover" alt="avatar">
              @else
                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center"
                     style="width:64px;height:64px;">?
                </div>
              @endif

              <div class="flex-fill">
                <label class="form-label mb-1">Photo (facultatif)</label>
                <input type="file" class="form-control" wire:model="photo" accept="image/*">
                @error('photo') <div class="small text-danger">{{ $message }}</div> @enderror
                <div wire:loading wire:target="photo" class="small text-muted mt-1">Chargement photo…</div>
              </div>
            </div>

            <div class="mb-2">
              <label class="form-label">Nom complet</label>
              <input type="text" class="form-control" wire:model="name" placeholder="Ex: Divin N.">
              @error('name') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" wire:model="email" placeholder="exemple@mail.com">
              @error('email') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label">Téléphone</label>
              <input type="text" class="form-control" wire:model="phone" placeholder="06XXXXXXXX">
              @error('phone') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-2">
              <label class="form-label">Rôle</label>
              <select class="form-select" wire:model="role">
                @foreach($this->roles as $r)
                  <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
              </select>
              @error('role') <div class="small text-danger">{{ $message }}</div> @enderror
            </div>

            @if($editingId && $password)
              <div class="alert alert-secondary d-flex justify-content-between align-items-center">
                <div class="small">
                  <div class="fw-semibold">Mot de passe actuel (fake)</div>
                  <code>{{ $password }}</code>
                </div>
                <button class="btn btn-sm btn-outline-secondary"
                        wire:click="copyPassword({{ $editingId }})">Copier</button>
              </div>
            @endif

            <div class="d-grid gap-2 mt-3">
              @if($editingId)
                <button class="btn btn-primary" wire:click="update">💾 Mettre à jour</button>
              @else
                <button class="btn btn-success" wire:click="create">➕ Créer</button>
              @endif
            </div>
          </div>
        </div>

        <div class="alert alert-info mt-3">
          <div class="small">Astuce</div>
          <ul class="small mb-0">
            <li>Le mot de passe est <strong>visible</strong> ici car c’est un <em>mode démo/fake</em>.</li>
            <li>En prod, ne stocke jamais le mot de passe en clair.</li>
          </ul>
        </div>
      </div>
    </aside>

  </div>

  <!-- Loader global -->
  <div wire:loading.flex class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 align-items-center justify-content-center" style="z-index:1055;">
    <div class="spinner-border" role="status"></div>
    <span class="ms-2 text-white">Chargement…</span>
  </div>

  <!-- JS: copie presse-papiers -->
  <script>
    window.addEventListener('copy-pwd', (e) => {
      const text = e.detail?.text || '';
      if (!navigator.clipboard) {
        // fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        Swal && Swal.fire({title:'Copié', text:'Mot de passe copié', icon:'success'});
        return;
      }
      navigator.clipboard.writeText(text).then(() => {
        Swal && Swal.fire({title:'Copié', text:'Mot de passe copié', icon:'success'});
      }).catch(() => {
        Swal && Swal.fire({title:'Erreur', text:'Impossible de copier', icon:'error'});
      });
    });
  </script>
</div>

</div>
