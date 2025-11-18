<div>
    <x-slot name="title_page">
        Module gestion
    </x-slot>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Parcourir les modules</h4>
        <div class="w-50">
            <input type="text" class="form-control" placeholder="Rechercher un module..."
                   wire:model.debounce.500ms="q">
        </div>
    </div>

    <div class="row g-3">
        @forelse($groups as $g)
            @php
                $slug = \Illuminate\Support\Str::slug($g->title);
            @endphp
            <div class="col-xl-3 col-sm-6">
                <div class="card pricing-box text-center h-100">
                    <div class="bg-primary-subtle p-5">
                        <div class="mb-3">
                            <i class="fas fa-file-archive text-primary h1"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $g->title }}</h5>
                        </div>
                    </div>
                    <div class="card-body position-relative">
                        <div class="text-center">
                            <h6 class="bg-success text-white px-3 py-1 d-inline-block rounded-pill position-absolute top-0 start-50 translate-middle">
                                Total Prof {{ $g->total_profs }}
                            </h6>
                            <div class="mt-4">
                                <ul class="list-unstyled plan-features small text-start d-inline-block">
                                    <li>
                                        <i class="mdi mdi-check-bold align-middle fs-16 text-success me-2"></i>
                                        Contenus totaux : <strong>{{ $g->total_contents ?? 0 }}</strong>
                                    </li>
                                    <li>
                                        <i class="mdi mdi-check-bold align-middle fs-16 text-success me-2"></i>
                                        Première création : <strong>{{ \Carbon\Carbon::parse($g->first_created_at)->format('d/m/Y') }}</strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('cand.modules.show', $g->code) }}" class="btn btn-primary w-md">Voir</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Aucun module trouvé.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $groups->links() }}
    </div>
</div>
