@extends('layouts.app')

@section('content')
<!-- Section Formulaire avec image de fond -->
<section class="relative bg-cover bg-center bg-no-repeat" style="background-image: url('assets/images/photo.png');" aria-labelledby="contact-form-title">
    <!-- Couche overlay pour lisibilité -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="relative container mx-auto px-4 py-16 md:py-24">
        <div class="max-w-12xl mx-auto  items-center">
            @livewire('liste')
        </div>
    </div>
</section>


@endsection
