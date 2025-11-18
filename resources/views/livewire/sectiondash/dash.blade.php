<div>

    <x-slot name="title_page">
        Page d'accueil
    </x-slot>

    @if(auth()->user()->statut == "admin")
        @livewire('dash.admin-dashboard')
    @endif
    @if(auth()->user()->statut == "candidat")
        @livewire('dash.dash-candidat')
    @endif
    @if(auth()->user()->statut == "professeur")
        @include('livewire.sectiondash.dash_professeur')
    @endif

</div>
