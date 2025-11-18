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
                    Vous êtes sur le point de finaliser votre inscription.
                </p>

                <h3 class="mt-6 text-xl font-semibold font-serif">Procédure</h3>
                <ul class="mt-2 space-y-1 text-sm opacity-90 list-disc list-inside">
                    <li>Insérez votre code d’inscription.</li>
                    <li>Cliquez sur <strong>Confirmer</strong>.</li>
                    <li>Validez le paiement en composant le <strong>*105#</strong>.</li>
                    <li>Nom du demandeur : <strong>SKYNOV TECHNOLOGIES</strong>, montant <b class="text-amber-400">{{ number_format(env('MONTANT_INITIAL'),0,","," ") }} XAF</b>.</li>
                    <li>Merci de noter votre mot de passe après la confirmation du paiement, afin de pouvoir vous connecter à votre compte pédagogique. Connectez vous <a href="{{ route('login') }}" class="link-info text-amber-400 " target="_blank">ici</a>  </strong>.</li>
                </ul>
            </div>


            <!-- Carte du formulaire -->
            @livewire('validate-inscription',['code'=>$code])
           
        </div>
    </div>
</section>


@endsection
