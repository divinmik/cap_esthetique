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
                Devenez Esthéticienne Certifiée
                </h2>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Une formation complète reconnue par l'État pour maîtriser l'art de l'esthétique cosmétique
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('ins') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105">
                    S'inscrire Maintenant
                </a>
                <a href="{{ route('paiement') }}" class="border-2 border-geen-600 text-green-600 hover:bg-green-50 font-bold py-3 px-8 rounded-full shadow transition-all duration-300">
                    Paiement inscription
                </a>
                </div>
            </div>
        </section>


        <!-- Presentation Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold font-serif mb-4 text-pink-600">Présentation du CAP Esthétique</h2>
                    <div class="w-24 h-1 bg-amber-400 mx-auto"></div>
                </div>
                
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    <div class="lg:w-1/2 flex justify-center">
                        <img 
                            src="{{ asset('assets/images/logo.png') }}" 
                            alt="Students learning" 
                            class="w-full max-w-[353px] h-[487px] object-cover"
                        >
                    </div>
                    <div class="lg:w-1/2">
                        <p class="text-lg mb-6">Le <strong class="text-pink-600">Certificat d'Aptitude Professionnelle en Esthétique</strong> est une formation diplômante reconnue par l'État, placée sous la tutelle du <strong>Ministère de l'Enseignement Technique et Professionnel</strong>.</p>
                        <p class="text-lg mb-8">Elle vise à former des esthéticiennes qualifiées, capables d'exercer dans les salons de beauté, instituts esthétiques ou à leur propre compte.</p>
                        
                        <div class="bg-pink-50 border-l-4 border-pink-600 p-6 rounded-r-lg">
                            <p class="text-lg italic font-medium text-pink-700">"Former la jeunesse aux métiers de l'esthétique, c'est investir dans l'excellence, l'autonomie et la valorisation de notre beauté."</p>
                            <p class="mt-2 text-sm text-pink-600">— Programme CAP Esthétique, Ministère de l'Enseignement Technique et Professionnel</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Video & Gallery Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Video Section -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-pink-600 text-white p-4">
                            <h3 class="text-xl font-bold flex items-center">
                                🎀 Formation Excellence
                            </h3>
                        </div>
                        <div class="relative pt-[56.25%]">
                            <video 
                                class="absolute top-0 left-0 w-full h-full object-cover" 
                                src="{{ asset('assets/videos/presentation_formation.mp4') }}" 
                                autoplay 
                                muted 
                                loop 
                                playsinline
                            ></video>
                        </div>
                    </div>

                    
                    <!-- Gallery Section -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-pink-600 text-white p-4">
                            <h3 class="text-xl font-bold flex items-center">
                                ✨ Nos Réalisations
                            </h3>
                        </div>

                        <div class="p-4">
                            <!-- Swiper -->
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Soins du visage" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Soins du visage</p>
                                    </div>
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Maquillage" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Maquillage</p>
                                    </div>
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Épilation" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Épilation</p>
                                    </div>
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Beauté des mains" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Beauté des mains</p>
                                    </div>
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Soins du corps" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Soins du corps</p>
                                    </div>
                                    <div class="swiper-slide text-center">
                                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Formation pratique" class="rounded-lg w-full">
                                        <p class="mt-2 font-semibold">Formation pratique</p>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="swiper-pagination mt-2"></div>

                                <!-- Navigation boutons -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold font-serif mb-4 text-pink-600">Coûts de la Formation</h2>
                    <div class="w-24 h-1 bg-amber-400 mx-auto"></div>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Pricing Card -->
                    <div class="bg-gradient-to-br from-pink-600 to-pink-500 text-white rounded-xl shadow-xl p-8 transform hover:scale-105 transition-transform">
                        <h3 class="text-2xl font-bold mb-2">Formation Complète</h3>
                        <div class="text-4xl font-bold mb-2">210 000 FCFA</div>
                        <div class="text-sm opacity-80 mb-6">9 mois de formation</div>
                        
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start">
                                <i data-feather="check" class="mr-2 mt-1 w-4 h-4"></i>
                                <span>7 modules spécialisés</span>
                            </li>
                            <li class="flex items-start">
                                <i data-feather="check" class="mr-2 mt-1 w-4 h-4"></i>
                                <span>Accès plateforme en ligne</span>
                            </li>
                            <li class="flex items-start">
                                <i data-feather="check" class="mr-2 mt-1 w-4 h-4"></i>
                                <span>Support formateur 7j/7</span>
                            </li>
                            <li class="flex items-start">
                                <i data-feather="check" class="mr-2 mt-1 w-4 h-4"></i>
                                <span>Certificat d'État inclus</span>
                            </li>
                        </ul>
                        
                        <a href="#" class="block w-full bg-white text-pink-600 text-center font-bold py-3 px-6 rounded-lg hover:bg-gray-100 transition">
                            Choisir cette formule
                        </a>
                    </div>
                    
                    <!-- Pricing Details -->
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-gray-800">Détail des Frais</h3>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-pink-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Élément</th>
                                        <th class="py-3 px-4 text-right">Montant</th>
                                        <th class="py-3 px-4 text-right">Période</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="py-3 px-4">Frais d'inscription</td>
                                        <td class="py-3 px-4 text-right">10 000 FCFA</td>
                                        <td class="py-3 px-4 text-right">Unique</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4">Mensualité</td>
                                        <td class="py-3 px-4 text-right">20 000 FCFA</td>
                                        <td class="py-3 px-4 text-right">× 9 mois</td>
                                    </tr>
                                    <tr class="font-bold bg-pink-50">
                                        <td class="py-3 px-4">Total formation</td>
                                        <td class="py-3 px-4 text-right">210 000 FCFA</td>
                                        <td class="py-3 px-4 text-right">9 mois</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            <h4 class="font-bold mb-3 text-gray-800">Modalités de Paiement</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="font-medium mb-1">Option 1</div>
                                    <p class="text-sm">Paiement unique 210 000 FCFA</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="font-medium mb-1">Option 2</div>
                                    <p class="text-sm">Mensuel 20 000 FCFA × 9 mois</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section officiels -->
        <section class="bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-pink-600 mb-10">
                    Sous le Haut Patronage
                </h2>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <div class="flex justify-center mt-6">
                            <img 
                                src="{{ asset('assets/images/ministre-maguessa.jpg') }}" 
                                alt="Ministre Ghyslain Thierri MAGUESSA EBOME" 
                                class="w-48 h-64 object-cover rounded-xl"
                            >
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800">Ghislain Thierry MAGUESSA EBOME</h3>
                            <div class="text-pink-600 font-semibold mt-1">MINISTRE</div>
                            <div class="text-gray-500 mt-1">Enseignement Technique et Professionnel</div>
                            <p class="mt-4 text-gray-600 italic">"Former aujourd'hui, c'est bâtir le Congo de demain."</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <div class="flex justify-center mt-6">
                            <img 
                                src="{{ asset('assets/images/promotrice-jeane.jpeg') }}" 
                                alt="MADAME JEANE DE POTO" 
                                class="w-48 h-64 object-cover rounded-xl"
                            >
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800">MADAME JEANE DE POTO</h3>
                            <div class="text-pink-600 font-semibold mt-1">COORDONATRICE ET PROMOTRICE</div>
                            <div class="text-gray-500 mt-1">CAP Esthétique</div>
                            <p class="mt-4 text-gray-600 italic">"Votre réussite est notre priorité absolue."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Modules Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold font-serif mb-4 text-pink-600">Programme de Formation</h2>
                    <div class="w-24 h-1 bg-amber-400 mx-auto"></div>
                    <p class="mt-4 text-lg max-w-2xl mx-auto">Notre programme complet couvre tous les aspects essentiels du métier d'esthéticienne</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Module 1 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/module-1.jpg') }}" alt="Accueil et Hygiène" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Accueil & Hygiène</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">2 semaines</span>
                            <p class="mb-4">Fondamentaux de l'accueil client et protocoles d'hygiène en institut.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Techniques d'accueil professionnel</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Normes d'hygiène et salubrité</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Module 2 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/soins-visage.png') }}" alt="Soins du Visage" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Soins du Visage</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">8 semaines</span>
                            <p class="mb-4">Maîtrise complète des techniques professionnelles de soins du visage.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Analyse types de peau</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Nettoyage et démaquillage</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Module 3 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/epilation.png') }}" alt="Épilation" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Épilation</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">8 semaines</span>
                            <p class="mb-4">Techniques d'épilation professionnelle pour tous types de peaux.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Cire chaude et froide</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Techniques jambes et aisselles</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Module 4 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/beaute-mains.png') }}" alt="Beauté des Mains" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Beauté Mains & Pieds</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">8 semaines</span>
                            <p class="mb-4">Soins complets de manucure et pédicure esthétique.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Manucure traditionnelle</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Pédicure esthétique</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Module 5 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/maquillage.png') }}" alt="Maquillage" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Maquillage</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">8 semaines</span>
                            <p class="mb-4">Art du maquillage professionnel adapté aux événements.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Maquillage jour et soirée</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Contouring et highlighting</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Module 6 -->
                    <div class="module-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <img src="{{ asset('assets/images/soin_corps.png') }}" alt="Soins du Corps" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-pink-600">Soins du Corps</h3>
                            <span class="inline-block bg-pink-100 text-pink-600 text-xs px-2 py-1 rounded-full mb-3">8 semaines</span>
                            <p class="mb-4">Massages relaxants et techniques de soins corporels.</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Massage relaxant</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-feather="check-circle" class="mr-2 text-green-500 w-4 h-4"></i>
                                    <span>Modelage corporel</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-pink-600 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-bold font-serif mb-6">Prête à transformer votre passion ?</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto">Rejoignez notre formation CAP Esthétique et lancez votre carrière dans l'esthétique.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('ins') }}" class="bg-white text-pink-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105">
                        S'inscrire Maintenant
                    </a>
                    <a href="#" class="border-2 border-white text-white hover:bg-pink-700 font-bold py-3 px-8 rounded-full shadow transition-all duration-300">
                        Nous Contacter
                    </a>
                </div>
            </div>
        </section>

        <!-- Section Partenaires -->
        <section class="bg-white py-12">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-pink-600 mb-10">
                    Nos Partenaires
                </h2>

                <!-- Grid des partenaires -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-6 items-center justify-items-center">
                    <img src="{{ asset('assets/images/partner1.jpeg') }}" alt="Partenaire 1" class="h-20 object-contain">
                    <img src="{{ asset('assets/images/partner1.jpeg') }}" alt="Partenaire 1" class="h-20 object-contain">
                    <img src="{{ asset('assets/images/partner1.jpeg') }}" alt="Partenaire 1" class="h-20 object-contain">
                </div>
            </div>
        </section>
@endsection