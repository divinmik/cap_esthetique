<div wire:ignore.self class="modal fade" id="save_prof" tabindex="-1" role="dialog" aria-labelledby="modal1Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal1Label">Module professeur</h5>
                <button type="button" class="btn btn-sm btn-label-danger btn-icon" data-bs-dismiss="modal" aria-label="Close">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="card-body">
                    @include('error_validate')
                    <form class="needs-validation" wire:submit.prevent="{{ $action === 'edit' ? 'edit' : 'save' }}">

                        <div class="row g-3">
                            {{-- Action --}}
                            <div class="col-12">
                                <label class="form-label">Action du mouvement *</label>
                                <div class="d-flex gap-4">
                                    <label class="inline-flex align-items-center gap-2">
                                        <input type="radio" wire:model.live="action" name="action" value="save" class="form-check-input">
                                        <span>Enregistrement</span>
                                    </label>
                                    <label class="inline-flex align-items-center gap-2">
                                        <input type="radio" wire:model.live="action" name="action" value="edit" class="form-check-input">
                                        <span>Mise à jour</span>
                                    </label>
                                </div>
                                @error('action')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($action=="edit")
                                {{-- lastname --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label" for="nom">Prof à editer *</label>
                                        <input type="text" class="form-control " list="liste_nom" wire:model.live="edit_prof"  required>
                                        <datalist id="liste_nom">
                                            @isset($datas)
                                            @foreach ($datas as $data)
                                            @if(!empty($data->lastname))
                                            <option value="{{ $data->code }}">{{ $data->firstname }} {{ $data->lastname }}</option>
                                            @endif
                                            @endforeach
                                            @endisset
                                        </datalist>
                                        
                                        <div class="invalid-feedback">{{ $user }}</div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Etat de compte *</label>
                                        <select wire:model.live="is_actif" class="form-control ">
                                            <option value="0">Désactivé</option>
                                            <option value="1">Activé</option>
                                        </select>
                                        
                                        @error('is_actif')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- lastname --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="nom">Nom *</label>
                                    <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" wire:model.live="lastname" autocomplete="family-name" required>
                                
                                    @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            

                            {{-- Prénom --}}
                            <div class="col-md-4">
                                <label class="form-label" for="firstname">Prénom *</label>
                                <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" wire:model.live="firstname" autocomplete="given-name" required>
                                @error('firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date de naissance --}}
                            <div class="col-md-4">
                                <label class="form-label" for="birthdate">Date de naissance *</label>
                                <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" wire:model.live="birthdate" autocomplete="given-name" required>
                                @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Lieu Date de naissance --}}
                            <div class="col-md-4">
                                <label class="form-label" for="birthplace">Lieu de naissance *</label>
                                <input id="birthplace" type="text" class="form-control @error('birthplace') is-invalid @enderror" wire:model.live="birthplace" autocomplete="given-name" required>
                                @error('birthplace')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Téléphone --}}
                            <div class="col-md-4">
                                <label class="form-label" for="phone">Téléphone *</label>
                                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" wire:model.live="phone" inputmode="tel" placeholder="06xxxxxxxx" autocomplete="tel" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- E-mail --}}
                            <div class="col-md-6">
                                <label class="form-label" for="email">E-mail</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" wire:model.live="email" autocomplete="email" placeholder="exemple@domaine.com">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Adresse --}}
                            <div class="col-md-6">
                                <label class="form-label" for="address">Adresse *</label>
                                <textarea id="address" class="form-control @error('address') is-invalid @enderror" wire:model.live="address" rows="3" required></textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Photo de profil --}}
                            <div class="col-md-6">
                                <label for="profile_photo_path" class="form-label">Photo de profil (JPG/PNG, max 2 Mo)</label>
                                <input id="profile_photo_path" name="profile_photo_path" type="file" accept="image/*" wire:model.live="profile_photo_path" class="form-control @error('profile_photo_path') is-invalid @enderror">

                                {{-- Progress/Loading (indéterminé) --}}
                                <div class="mt-2" wire:loading wire:target="profile_photo_path">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div>
                                    </div>
                                    <small class="text-success">Téléchargement en cours…</small>
                                </div>

                                @error('profile_photo_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                {{-- Aperçu temporaire / photo existante --}}
                                <div class="mt-2">
                                    @if ($profile_photo_path)
                                    <img src="{{ $profile_photo_path->temporaryUrl() }}" alt="Aperçu" class="rounded border" style="max-width:140px;">
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="$set('profile_photo_path', null)">
                                            Retirer la sélection
                                        </button>
                                    </div>
                                    @elseif ($action === 'edit' && !empty($this->user->profile_photo_path))
                                    @php
                                    $fille = str_replace('profils/', '', $this->user->profile_photo_path);
                                    @endphp
                                    <img src="{{ route('docs.display', ['filename' => $fille]) }}" alt="Photo actuelle" class="rounded border" style="max-width:140px;">
                                    <div class="form-text">Photo actuelle</div>
                                    @endif
                                </div>
                            </div>

                            @error('profile_photo_path')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer mt-3">
                            @if ($action === 'edit')
                            <button type="submit" class="btn btn-warning">
                                Mettre à jour
                                @if(!empty($ecole?->nom))
                                <b class="text-primary">{{ $ecole->nom }}</b>
                                @endif
                            </button>
                            @else
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            @endif

                            <button type="button" wire:click="resets" class="btn btn-outline-danger">Effacer</button>
                        </div>
                    </form>


                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer la page</button></div>
        </div>
    </div>
</div>
