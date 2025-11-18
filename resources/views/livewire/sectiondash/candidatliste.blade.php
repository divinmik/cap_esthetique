<div>
     <x-slot name="title_page">
        Candidats
    </x-slot>
   <div class="row">
    <div class="col-12">
        <div class="card" style="height: 495px; overflow: hidden auto;" data-simplebar="">
            <div class="card-header">
                <div class="card-icon text-muted"><i class="fas fa-sync-alt fs-14"></i></div>
                <h3 class="card-title">Candidats inscrits 
                    <div class="avatar avatar-primary avatar-circle avatar-xs">
                        <span class="avatar-display">{{ count($candidates) }}</span>
                    </div>
                </h3>
                
                <div class="card-addon dropdown">
                    <button class="btn btn-label-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown"> <i class="fas fa-filter fs-12 align-middle ms-1"></i></button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated">
                        <a class="dropdown-item" href="/#">
                            <div class="dropdown-icon"><i class="fa fa-poll"></i></div>
                            <span class="dropdown-content">Enrégistre/Mis à jours</span>
                        </a>
                        <a class="dropdown-item" href="/#">
                            <div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div>
                            <span class="dropdown-content">Yesterday</span>
                        </a>
                        <a class="dropdown-item" href="/#">
                            <div class="dropdown-icon"><i class="fa fa-chart-line"></i></div>
                            <span class="dropdown-content">Week</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table text-nowrap mb-0">
                       <thead class="bg-gray-50">
                        <tr>
                            <th class="" wire:click="sortBy('id')">#</th>
                            <th class="">Code</th>

                            <!-- Nouvelle colonne Profil -->
                            <th class="">Profil</th>

                            <th class="" wire:click="sortBy('lastname')">Nom</th>
                            <th class="">Prénom</th>
                            <th class="">Date de naissance</th>
                            <th class="" wire:click="sortBy('email')">Email</th>
                            <th class="">Téléphone</th>
                            <th class="">Adresse</th>
                            <th class="">Ville Choisie</th>
                            <th class="">Type de formation</th>
                            <th class="">Niveau atteint</th>
                            <th class="">Etre mis(e) en contact avec un salon partenaire</th>
                            <th class="">Statut</th>
                            <th class="">Date</th>
                            <th class="">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($candidates as $key=>$candidate)
                            <tr class="hover:bg-gray-50">
                            <td class=" font-medium text-gray-700">{{ $key+=1 }}</td>
                            <td class=" font-medium text-gray-700"># {{ $candidate->code }}</td>

                            <!-- Cellule Profil : avatar + nom complet (plus grande) -->
                            <td class="">
                                @php
                                $fille = str_replace('profils/', '', $candidate->profile_photo_path);
                                @endphp 
                                <div class="avatar-group">
                                    <div class="avatar avatar-circle">
                                        <img
                                            src="{{ route('docs.display', ['filename' => $fille]) }}"
                                            alt="Avatar {{ $candidate->lastname }}"
                                            class="avatar-2xs"
                                        />
                                    </div>
                                </div>
                                
                            </td>

                            <td class="">{{ $candidate->lastname }}</td>
                            <td class="">{{ $candidate->firstname }}</td>
                            <td class="">{{ $candidate->birthdate}}</td>
                            <td class="">{{ $candidate->email }}</td>
                            <td class="">{{ $candidate->phone }}</td>
                            <td class="">{{ $candidate->address }}</td>
                            <td class="">{{ $candidate->city }}</td>
                            <td class="">{{ $candidate->type_formation }}</td>
                            <td class="">{{ $candidate->level }}</td>
                            <td class="">{{ $candidate->contact_salon }}</td>
                            <td class="">
                                <button
                                class="btn {{ ($candidate->is_valide==1)?'btn-label-success':'btn-label-warning' }} ">
                                    {{ ($candidate->is_valide==1)?"Paiement completé":'En attente de paiement' }}
                                </button>
                            </td>
                            <td class="">{{ $candidate->created_at->format('d/m/Y H:i') }}</td>

                            <td class="">
                                <div class="flex justify-end items-center gap-3">
                                @if($fille)
                                    <a href="{{route('docs.display', ['filename' => $fille]) }}" target="_blank" class="text-sm text-blue-600 underline">Photo</a>
                                @endif

                                <button
                                    onclick="confirm('Supprimer cet enregistrement ?') || event.stopImmediatePropagation()"
                                    wire:click="deleteCandidate({{ $candidate->id }})"
                                    class="btn btn-label-danger"
                                >
                                    Suppr
                                </button>
                                </div>
                            </td>
                            </tr>
                        @empty
                            
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 {{-- Because she competes with no one, no one can compete with her. --}}
</div>
