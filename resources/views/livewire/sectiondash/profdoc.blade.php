<div>

    <x-slot name="title_page">
        Profil {{ $data->lastname }} {{ $data->firstname }}
    </x-slot>

     <div class="row">
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
                                @php
                                $fille = str_replace('profils/', '', $data->profile_photo_path);
                                @endphp  
                             <img src="/admin/assets/images/contact.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row align-items-end">
                        <div class="col-sm-4">
                            <div class="avatar-md mb-3 mt-n4">
                                <img src="{{ route('docs.display', ['filename' => $fille]) }}" alt="" class="img-fluid avatar-circle bg-light p-2 border-2 border-primary">
                            </div>
                            <h5 class="fs-16 mb-1 text-truncate">{{ $data->firstname }} {{ $data->lastname }}</h5>
                        </div>

                        
                    </div>
                </div>

                <div class="card-body border-top">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row"><i class="mdi mdi-account align-middle text-primary me-2"></i> Nom :</th>
                                    <td>{{ $data->lastname }} {{ $data->firstname }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="mdi mdi-cellphone align-middle text-primary me-2"></i> Téléphone :</th>
                                    <td>{{ $data->phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="mdi mdi-calendar-account-outline text-primary me-2"></i> Date de naissance :</th>
                                    <td>{{ $data->birthdate }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="mdi mdi-google-maps text-primary me-2"></i> Lieu de naissance :</th>
                                    <td>{{ $data->birthplace }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="mdi mdi-email text-primary me-2"></i> E-mail :</th>
                                    <td>{{ $data->email }}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- end card -->

        </div>

        <div class="col-xl-9">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="mdi mdi-school"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted fw-medium mb-2">Module enrégistré</p>
                                    <h4 class="mb-0">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
                                        <span class="avatar-title">
                                            <i class="mdi mdi-timer-sand fs-24"></i>
                                        </span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="card">
                
                <div class="card-body">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="nav nav-lines mb-0" id="nav1-tab" role="tablist">
                                <a class="nav-item nav-link {{ ($show == 'liste_nv')?'active':'' }}" href="#" wire:click.prevent="tabs('liste_nv')">Modules</a>
                                <a class="nav-item nav-link {{ ($show == 'liste_py')?'active':'' }}" href="#" wire:click.prevent="tabs('liste_py')">Changement mot de passe</a>
                            </div>
                        </div>
                        <div class="tab-content" id="nav1-tabContent">
                            @if ($show == 'liste_nv')
                            <div class="tab-pane fade active show" id="nav1-home" role="tabpanel" aria-labelledby="#nav1-home-tab">
                                <h2>
                                    Liste module
                                </h2>
                                <div>
                                    <table id="datatable-buttons" class="table table-hover table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mois/Année</th>
                                                <th>Titre</th>
                                                <th>Description</th>
                                                <th>Total contenu</th>
                                                <th>Date création</th>
                                                <th>Détail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            @endif

                         

                            @if ($show == 'liste_py')
                            <div class="tab-pane fade active show" id="nav1-contact" role="tabpanel" aria-labelledby="#nav1-contact-tab">
                                <h2>
                                    Changement du mot de passe
                                </h2>
                                 <div>
                                    @if(session('status'))
                                        <div class="alert alert-success mb-3">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form class="needs-validation" wire:submit.prevent="save_password" novalidate>
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Ancien mot de passe *</label>
                                                    <input type="password"
                                                        class="{{ $errors->has('old_password') ? 'form-control is-invalid' : 'form-control' }}"
                                                        wire:model.defer="old_password" />

                                                    @error('old_password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Nouveau mot de passe *</label>
                                                    <input type="password"
                                                        class="{{ $errors->has('password') ? 'form-control is-invalid' : 'form-control' }}"
                                                        wire:model.defer="password" />

                                                    @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror

                                                    <div class="form-text">
                                                        Minimum 8 caractères.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Confirmer le mot de passe *</label>
                                                    <input type="password"
                                                        class="{{ $errors->has('password_confirmation') ? 'form-control is-invalid' : 'form-control' }}"
                                                        wire:model.defer="password_confirmation" />

                                                    @error('password_confirmation')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary"
                                                    wire:loading.attr="disabled">
                                                Enregistrer
                                            </button>

                                            <button type="button" wire:click="resets" class="btn btn-outline-danger">
                                                Effacer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
