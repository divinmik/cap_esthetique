@extends('layouts.app')

@section('content')
            <!-- Hero Section -->
        <section class="relative py-16 overflow-hidden">
            <!-- GIF arrière-plan (hidden from screen readers) -->
            <img src="{{ asset('assets/images/giphy2.gif') }}"
                alt=""
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 w-full h-full object-cover -z-10 opacity-80"
                style="filter: saturate(1.05) blur(0px);" />

            <!-- Overlay pour améliorer lisibilité -->
            <div class="absolute inset-0 -z-5 bg-gradient-to-r from-pink-50/60 to-amber-50/60"></div>
            <!-- (optionnel) un voile sombre léger -->
            <div class="absolute inset-0 -z-4 bg-black/10"></div>

            <div class="container mx-auto px-4 text-center relative z-10">
                <h2 class="text-4xl md:text-5xl font-bold font-serif mb-6 text-pink-600">
                519
                </h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    Page expirée merci
                </p>
            </div>
        </section>

@endsection