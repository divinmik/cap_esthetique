<div>
    <x-slot name="title_page">
        Profil {{ $teacher->lastname }} {{ $teacher->firstname }}
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
                                <p class="mb-0">Espace professeur</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="align-self-end">
                                <img src="/admin/assets/images/contact.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div class="row align-items-end">
                        <div class="col-sm-4">
                            <div class="avatar-md mb-3 mt-n4">
                                @if($teacher->profile_photo_path)
                                    <img src="{{ Storage::url($teacher->profile_photo_path) }}"
                                         alt="photo"
                                         class="img-fluid avatar-circle bg-light p-2 border-2 border-primary">
                                @else
                                    <img src="/admin/assets/images/placeholder-user.png"
                                         alt="photo"
                                         class="img-fluid avatar-circle bg-light p-2 border-2 border-primary">
                                @endif
                            </div>
                            <h5 class="fs-16 mb-1 text-truncate">
                                {{ $teacher->firstname }} {{ $teacher->lastname }}
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
                                <td>{{ $teacher->lastname }} {{ $teacher->firstname }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-cellphone text-primary me-2"></i> Téléphone :</th>
                                <td>{{ $teacher->phone }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-calendar-account-outline text-primary me-2"></i> Naissance :</th>
                                <td>{{ optional($teacher->birthdate)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-google-maps text-primary me-2"></i> Lieu :</th>
                                <td>{{ $teacher->birthplace }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-email text-primary me-2"></i> E-mail :</th>
                                <td>{{ $teacher->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="mdi mdi-pound text-primary me-2"></i> Code :</th>
                                <td class="fw-bold">{{ $teacher->code }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- COLONNE DROITE -->
        <div class="col-xl-9">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body d-flex">
                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                    <span class="avatar-title">
                                        <i class="mdi mdi-school"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted fw-medium mb-2">Modules enregistrés</p>
                                <h4 class="mb-0">{{ $modules->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success mb-3">{{ session('status') }}</div>
                    @endif

                    <div class="mb-3">
                        <div class="nav nav-lines" role="tablist">
                            <a class="nav-item nav-link {{ $tab==='modules'?'active':'' }}"
                               href="#"
                               wire:click.prevent="setTab('modules')">Modules</a>
                            <a class="nav-item nav-link {{ $tab==='profile'?'active':'' }}"
                               href="#"
                               wire:click.prevent="setTab('profile')">Profil</a>
                            <a class="nav-item nav-link {{ $tab==='password'?'active':'' }}"
                               href="#"
                               wire:click.prevent="setTab('password')">Mot de passe</a>
                        </div>
                    </div>

                    <div class="tab-content">
                        {{-- ================= TAB MODULES ================= --}}
                        @if($tab==='modules')
                        <div class="tab-pane fade active show">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ $editingModuleId ? 'Éditer le module' : 'Nouveau module' }}</h5>
                            </div>

                            <form wire:submit.prevent="saveModule" class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Titre *</label>
                                    <input type="text"
                                           class="form-control @error('module_title') is-invalid @enderror"
                                           wire:model.defer="module_title">
                                    @error('module_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <input type="text"
                                           class="form-control @error('module_description') is-invalid @enderror"
                                           wire:model.defer="module_description">
                                    @error('module_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-2 d-flex align-items-end gap-2">
                                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                                        {{ $editingModuleId ? 'Mettre à jour' : 'Enregistrer' }}
                                    </button>
                                    @if($editingModuleId)
                                        <button type="button" class="btn btn-outline-secondary" wire:click="resetModuleForm">Annuler</button>
                                    @endif
                                </div>
                            </form>

                            <hr>

                            <h5 class="mb-3">Liste des modules</h5>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mois/Année</th>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Total contenu</th>
                                        <th>Date création</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($modules as $i => $m)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $m->created_at->format('m/Y') }}</td>
                                            <td class="fw-semibold">{{ $m->title }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($m->description, 80) }}</td>
                                            <td>{{ $m->contents_count }}</td>
                                            <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-nowrap">
                                                <button class="btn btn-sm btn-outline-primary" wire:click="editModule({{ $m->id }})">Éditer</button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                        wire:click="deleteModule({{ $m->id }})"
                                                        onclick="return confirm('Supprimer ce module ?')">Supprimer</button>
                                                <button class="btn btn-sm btn-success" wire:click="setModuleForContent({{ $m->id }})">Contenus</button>
                                            </td>
                                        </tr>

                                        {{-- BLOC GESTION CONTENUS DU MODULE --}}
                                        @if($moduleIdForContent === $m->id)
                                            <tr>
                                                <td colspan="7">
                                                    <div class="p-3 border rounded bg-light">
                                                        <h6 class="mb-3">
                                                            Ajouter un contenu à <span class="fw-bold">{{ $m->title }}</span>
                                                        </h6>

                                                        <form wire:submit.prevent="saveContent" class="row g-2">
                                                            <input type="hidden" wire:model.live="moduleIdForContent">

                                                            <div class="col-md-3">
                                                                <label class="form-label">Titre *</label>
                                                                <input type="text"
                                                                       class="form-control @error('content_title') is-invalid @enderror"
                                                                       wire:model.defer="content_title">
                                                                @error('content_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="form-label">Type *</label>
                                                                <select class="form-select @error('content_type') is-invalid @enderror"
                                                                        wire:model.live="content_type">
                                                                    <option value="pdf">PDF</option>
                                                                    <option value="image">Image</option>
                                                                    <option value="audio">Audio</option>
                                                                    <option value="file">Fichier</option>
                                                                   <option value="video_file">Vidéo (fichier)</option>
                                                                    {{-- <option value="video_url">Lien vidéo</option> --}}
                                                                </select>
                                                                @error('content_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                            </div>

                                                            @if($content_type === 'video_url')
                                                                <div class="col-md-4">
                                                                    <label class="form-label">URL vidéo *</label>
                                                                    <input type="url"
                                                                           class="form-control @error('content_url') is-invalid @enderror"
                                                                           placeholder="https://youtube.com/..."
                                                                           wire:model.defer="content_url">
                                                                    @error('content_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                                </div>
                                                            @else
                                                                <div class="col-md-4">
                                                                    <label class="form-label">
                                                                        Fichier *
                                                                        @if($content_type==='video_file')
                                                                            <small class="text-muted">(mp4, webm, mov…)</small>
                                                                        @endif
                                                                    </label>
                                                                    <input type="file"
                                                                           class="form-control @error('content_file') is-invalid @enderror"
                                                                           wire:model.live="content_file"
                                                                           accept="
                                                                            @if($content_type==='video_file') video/*
                                                                            @elseif($content_type==='pdf') .pdf
                                                                            @elseif($content_type==='image') image/*
                                                                            @elseif($content_type==='audio') audio/*
                                                                            @else * @endif
                                                                           ">
                                                                    @error('content_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                                    <div wire:loading wire:target="content_file" class="form-text">Téléversement...</div>
                                                                </div>
                                                            @endif

                                                            <div class="col-md-2 d-flex align-items-end">
                                                                <button class="btn btn-success w-100" type="submit" wire:loading.attr="disabled">
                                                                    Ajouter
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <hr>

                                                        <h6 class="mb-2">Contenus du module</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-striped align-middle">
                                                                <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Titre</th>
                                                                    <th>Type</th>
                                                                    <th>Actions</th>
                                                                    <th>Ajouté le</th>
                                                                    <th></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($m->contents as $k => $c)
                                                                    <tr>
                                                                        <td>{{ $k+1 }}</td>
                                                                        <td>
                                                                        @if($editingContentId === $c->id)
                                                                            <form class="d-flex gap-2" wire:submit.prevent="saveContentTitle">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm @error('editingContentTitle') is-invalid @enderror"
                                                                                    wire:model.defer="editingContentTitle"
                                                                                    placeholder="Titre du contenu">
                                                                                @error('editingContentTitle')
                                                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                                                @enderror

                                                                                <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                                                                                    Enregistrer
                                                                                </button>
                                                                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="cancelEditContentTitle">
                                                                                    Annuler
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <span class="fw-semibold">{{ $c->title }}</span>
                                                                            <button type="button"
                                                                                    class="btn btn-xs btn-link text-decoration-none ms-2"
                                                                                    wire:click="startEditContentTitle({{ $c->id }})">
                                                                                <i class="mdi mdi-pencil"></i> éditer
                                                                            </button>
                                                                        @endif
                                                                    </td>

                                                                        <td class="text-uppercase">{{ $c->type }}</td>
                                                                        <td class="text-nowrap">
                                                                            {{-- Bouton PREVIEW selon type --}}
                                                                            @if($c->type === 'video_url')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-outline-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#previewModal"
                                                                                        data-kind="video_url"
                                                                                        data-title="{{ $c->title }}"
                                                                                        data-src="{{ $c->url }}">
                                                                                    Prévisualiser
                                                                                </button>
                                                                                <a href="{{ $c->url }}" target="_blank" class="btn btn-xs btn-outline-secondary ms-1">Ouvrir</a>
                                                                            @elseif($c->type === 'video_file')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-outline-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#previewModal"
                                                                                        data-kind="video_file"
                                                                                        data-title="{{ $c->title }}"
                                                                                        data-src="{{ Storage::url($c->path) }}">
                                                                                    Prévisualiser
                                                                                </button>
                                                                                <a href="{{ Storage::url($c->path) }}" target="_blank" class="btn btn-xs btn-outline-secondary ms-1">Ouvrir</a>
                                                                            @elseif($c->type === 'image')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-outline-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#previewModal"
                                                                                        data-kind="image"
                                                                                        data-title="{{ $c->title }}"
                                                                                        data-src="{{ Storage::url($c->path) }}">
                                                                                    Prévisualiser
                                                                                </button>
                                                                                <a href="{{ Storage::url($c->path) }}" target="_blank" class="btn btn-xs btn-outline-secondary ms-1">Ouvrir</a>
                                                                            @elseif($c->type === 'pdf')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-outline-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#previewModal"
                                                                                        data-kind="pdf"
                                                                                        data-title="{{ $c->title }}"
                                                                                        data-src="{{ Storage::url($c->path) }}">
                                                                                    Prévisualiser
                                                                                </button>
                                                                                <a href="{{ Storage::url($c->path) }}" target="_blank" class="btn btn-xs btn-outline-secondary ms-1">Ouvrir</a>
                                                                            @elseif($c->type === 'audio')
                                                                                <button type="button"
                                                                                        class="btn btn-xs btn-outline-primary"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#previewModal"
                                                                                        data-kind="audio"
                                                                                        data-title="{{ $c->title }}"
                                                                                        data-src="{{ Storage::url($c->path) }}">
                                                                                    Prévisualiser
                                                                                </button>
                                                                                <a href="{{ Storage::url($c->path) }}" target="_blank" class="btn btn-xs btn-outline-secondary ms-1">Ouvrir</a>
                                                                            @else
                                                                                <a href="{{ Storage::url($c->path) }}" download class="btn btn-xs btn-outline-secondary">Télécharger</a>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                                                        <td class="text-end">
                                                                            <button class="btn btn-xs btn-outline-danger"
                                                                                    wire:click="deleteContent({{ $c->id }})"
                                                                                    onclick="return confirm('Supprimer ce contenu ?')">Supprimer</button>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="6" class="text-center text-muted">Aucun contenu.</td>
                                                                    </tr>
                                                                @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Aucun module pour le moment.</td>
                                        </tr>
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
                                    <input type="password"
                                           class="form-control @error('old_password') is-invalid @enderror"
                                           wire:model.defer="old_password">
                                    @error('old_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nouveau mot de passe *</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           wire:model.defer="password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">Minimum 8 caractères.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirmer *</label>
                                    <input type="password"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
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

    {{-- ======= MODAL DE PRÉVISUALISATION (VIDÉO / PDF / IMAGE / AUDIO) ======= --}}
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="previewModalLabel">Prévisualisation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            {{-- conteneurs --}}
            <div id="previewContainer" class="d-none">
              {{-- Le script injecte ici : <iframe> / <video> / <img> / <audio> --}}
            </div>
            <div id="previewRatios" class="ratio ratio-16x9 d-none"></div>
            <div id="previewFallback" class="text-center text-muted">Chargement de l’aperçu…</div>
          </div>
        </div>
      </div>
    </div>
</div>

@push('scripts')
<script>
(function(){
  const modalEl = document.getElementById('previewModal');
  const titleEl = document.getElementById('previewModalLabel');
  const container = document.getElementById('previewContainer');
  const ratios = document.getElementById('previewRatios');
  const fallback = document.getElementById('previewFallback');

  function clearPreview(){
    container.innerHTML = '';
    container.classList.add('d-none');
    ratios.innerHTML = '';
    ratios.classList.add('d-none');
    fallback.classList.remove('d-none');
  }

  // Convertit YouTube/Vimeo en embed quand c'est possible
  function toEmbed(url){
    try {
      const u = new URL(url);
      // YouTube
      if (u.hostname.includes('youtube.com')) {
        const vid = u.searchParams.get('v');
        if (vid) return `https://www.youtube.com/embed/${vid}`;
      }
      if (u.hostname === 'youtu.be') {
        const vid = u.pathname.replace('/','');
        if (vid) return `https://www.youtube.com/embed/${vid}`;
      }
      // Vimeo
      if (u.hostname.includes('vimeo.com')) {
        const id = u.pathname.split('/').filter(Boolean).pop();
        if (id) return `https://player.vimeo.com/video/${id}`;
      }
      // Lien direct vidéo : on lira en <video>
      if (/\.(mp4|webm|ogg|mov)(\?.*)?$/i.test(url)) return null;
      // Sinon iFrame générique (si autorisé par le domaine)
      return url;
    } catch(e){ return url; }
  }

  modalEl.addEventListener('show.bs.modal', function (event) {
    clearPreview();
    const btn = event.relatedTarget;
    const kind = btn?.getAttribute('data-kind');
    const src  = btn?.getAttribute('data-src');
    const title= btn?.getAttribute('data-title') || 'Prévisualisation';
    titleEl.textContent = title;

    if (!src) return;

    let node = null;
    let useRatio = false;

    switch(kind){
      case 'video_url': {
        const embed = toEmbed(src);
        if (embed) {
          node = document.createElement('iframe');
          node.setAttribute('allowfullscreen', 'true');
          node.setAttribute('frameborder', '0');
          node.src = embed;
          useRatio = true;
        } else {
          node = document.createElement('video');
          node.setAttribute('controls','true');
          node.setAttribute('preload','metadata');
          node.style.width = '100%';
          const source = document.createElement('source');
          source.src = src;
          node.appendChild(source);
          useRatio = true;
        }
        break;
      }
      case 'video_file': {
        node = document.createElement('video');
        node.setAttribute('controls','true');
        node.setAttribute('preload','metadata');
        node.style.width = '100%';
        const source = document.createElement('source');
        source.src = src;
        node.appendChild(source);
        useRatio = true;
        break;
      }
      case 'pdf': {
        // Utilise iframe (ou <embed>) pour PDF
        node = document.createElement('iframe');
        node.src = src + '#toolbar=1&navpanes=0&scrollbar=1';
        node.style.width = '100%';
        node.style.height = '80vh';
        break;
      }
      case 'image': {
        node = document.createElement('img');
        node.src = src;
        node.alt = title;
        node.className = 'img-fluid';
        break;
      }
      case 'audio': {
        node = document.createElement('audio');
        node.setAttribute('controls','true');
        node.style.width = '100%';
        const source = document.createElement('source');
        source.src = src;
        node.appendChild(source);
        break;
      }
      default: {
        // Fichiers divers : on essaye iframe, sinon on laisse le fallback
        node = document.createElement('iframe');
        node.src = src;
        node.style.width = '100%';
        node.style.height = '70vh';
      }
    }

    if (node) {
      if (useRatio) {
        ratios.innerHTML = '';
        ratios.appendChild(node);
        ratios.classList.remove('d-none');
      } else {
        container.innerHTML = '';
        container.appendChild(node);
        container.classList.remove('d-none');
      }
      fallback.classList.add('d-none');
    }
  });

  modalEl.addEventListener('hidden.bs.modal', clearPreview);
})();
</script>
@endpush
