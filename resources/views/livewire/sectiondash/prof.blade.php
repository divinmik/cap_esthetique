<div>
     <x-slot name="title_page">
        Gestion prof
    </x-slot>
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   <p>
                        <button class="btn btn-flat-primary" data-bs-toggle="modal" data-bs-target="#save_prof">Enrégistre/Mis à jours</button>
                    </p>
                </div>
                <div class="card-body">
                    

                    <div wire:ignore>
                        <table id="datatable-buttons" class="table table-hover table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Profil</th>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Téléphone</th>
                                    <th>E-mail</th>
                                    <th>Adresse</th>
                                    <th>Nombre module</th>
                                    <th>Etat compte</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $k=>$data)
                                    <tr>
                                        <td>{{ $k+=1 }}</td>
                                        <td>
                                            @php
                                            $fille = str_replace('profils/', '', $data->profile_photo_path);
                                            @endphp 
                                            <div class="avatar-group">
                                                <div class="avatar avatar-circle">
                                                    <a href="{{route('docs.display', ['filename' => $fille]) }}" target="_blank">
                                                    <img
                                                        src="{{ route('docs.display', ['filename' => $fille]) }}"
                                                        alt="Avatar {{ $data->lastname }}"
                                                        class="avatar-2xs"
                                                    />
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $data->code }}</td>
                                        <td><a href="{{ route('docprof',$data->code) }}" target="_blanck">{{ $data->lastname }}<a></td>
                                        <td>{{ $data->firstname }}</td>
                                        <td>{{ $data->phone }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->address }}</td>
                                        <td>{{ count($data->modules) }}</td>
                                        <td><button class="btn btn-sm btn-label-{{ ($data->is_actif==1)?'success':'danger' }}">{{ ($data->is_actif==1)?'Activé':'Désactivé' }}</button></td>
                                    </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include("livewire.sectiondash.model-edit-prof")
</div>
