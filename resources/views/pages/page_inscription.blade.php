@extends('layouts.app')

@section('content')
<!-- Section Formulaire avec image de fond -->
<section class="relative bg-cover bg-center bg-no-repeat" style="background-image: url('assets/images/photo.png');" aria-labelledby="contact-form-title">
    <!-- Couche overlay pour lisibilité -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="relative container mx-auto px-4 py-16 md:py-24">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

            <!-- Texte / Accroche -->
            <div class="text-white space-y-4">
                <h2 id="contact-form-title" class="text-3xl md:text-4xl font-bold font-serif">
                   CAP Esthétique
                </h2>
                <p class="opacity-90">
                    Inscrivez-vous dès aujourd'hui pour nos formations professionnelles en esthétique.
                    Stages encadrés, pratique intensive et diplôme reconnu.
                </p>

                <h3 class="mt-6 text-xl font-semibold font-serif">Conditions d’admission</h3>
                <ul class="mt-2 space-y-1 text-sm opacity-90 list-disc list-inside">
                    <li>Être âgé de 16 ans minimum</li>
                    <li>Avoir un niveau 3ème ou équivalent</li>
                    <li>Remplir le formulaire d’inscription</li>
                    <li>Fournir les pièces demandées</li>
                </ul>

                <h3 class="mt-4 text-xl font-semibold font-serif">Pièces à fournir</h3>
                <ul class="mt-2 space-y-1 text-sm opacity-90 list-disc list-inside">
                    <li>1 copie de la pièce d’identité (CNI ou passeport)</li>
                    <li>2 photos d’identité récentes</li>
                    <li>1 extrait de naissance</li>
                    <li>1 photocopie du dernier diplôme ou bulletin</li>
                    <li>Frais d’inscription : 10 000 FCFA</li>
                </ul>
            </div>


            <!-- Carte du formulaire -->
            @livewire('signup-form')
        </div>
    </div>
</section>


@endsection
