<div>

    <x-slot name="title_page">
        Page d'accueil
    </x-slot>

    @if(auth()->user()->statut == "admin")
        @include('livewire.sectiondash.dash_admin')
    @endif
    @if(auth()->user()->statut == "candidat")
        @include('livewire.sectiondash.dash_candidat')
    @endif
    @if(auth()->user()->statut == "professeur")
        @include('livewire.sectiondash.dash_professeur')
    @endif

</div>
