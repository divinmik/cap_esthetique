<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formation CAP Esthétique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>

      /* Bannière texte défilant */
       .scrolling-banner {
            background: transparent;
            padding: 15px 0;
            overflow: hidden;
            position: relative;
            height: 50px; /* ← Ajoutez une hauteur fixe */
        }

            .scrolling-text {
            white-space: nowrap;
            animation: scrollBanner 75s linear infinite; /* ← Animation plus lente */
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--accent-gold);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            display: inline-block; /* ← Important pour l'animation */
        }

        .scrolling-text span {
            margin: 0 60px; /* ← Plus d'espace entre les textes */
            display: inline-block;
        }
            @keyframes scrollBanner {
            0% { 
                transform: translateX(100vw);
            }
            100% { 
                transform: translateX(-100%);
            }
        }

        /* Barre de séparation décorative */
        .decoration-bar {
            background: linear-gradient(90deg, 
                var(--primary-pink) 0%, 
                var(--light-pink) 20%, 
                var(--accent-gold) 40%, 
                var(--light-pink) 60%, 
                var(--primary-pink) 80%,
                var(--dark-pink) 100%);
            height: 4px;
            position: relative;
            overflow: hidden;
        }

        .decoration-bar::before {
            content: '💖 🌸 💄 ✨ 💅 🌷 🎀 💐 🌹 💋';
            position: absolute;
            white-space: nowrap;
            animation: scrollText 20s linear infinite;

            color: white;
            font-size: 20px;
            line-height: 4px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            font-weight: bold;
        }

        @keyframes scrollText {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        :root {
            --primary-pink: #FF5E8A;
            --secondary-gold: #FFD700;
            --dark-pink: #D23369;
            --light-pink: #FFE5EC;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fafafa;
            color: #333;
        }
        .vanta-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
        }
        .header-gradient {
            background: linear-gradient(135deg, #d91b60 0%, rgba(251, 255, 0, 0.99) 100%);
        }
        .nav-btn {
            transition: all 0.3s ease;
        }
        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .module-card {
            transition: all 0.3s ease;
        }
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .active{
            background-color: #db2777 !important;
            color: white !important;
        }
     
    </style>
    <!-- Utilities -->
<style>
  .scrollbar-hide::-webkit-scrollbar { display: none; }
  .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
    @livewireStyles
   
</head>
<body class="relative overflow-x-hidden">
    <div id="vanta-bg" class="vanta-bg"></div>
    <div class="content-overlay">
        <!-- Header Section -->
        <header class="header-gradient text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    
                    <!-- Logo Section -->
                    <div class="flex items-center mb-6 md:mb-0">
                        <img src="/assets/images/logo.png" alt="CAP Esthétique"
                            class="w-16 h-16 rounded-full border-2 border-white mr-4">
                        <div>
                            <h1 class="text-2xl font-bold font-serif">CAP Esthétique</h1>
                            <p class="text-sm opacity-80">
                                Ministère de l'Enseignement Technique et Professionnel
                            </p>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('home') }}"
                        class="nav-btn  {{ request()->routeIs('/') ? 'active' : '' }} bg-white text-pink-600 px-4 py-2 rounded-full font-medium flex items-center">
                            <i data-feather="home" class="mr-2 w-4 h-4"></i> Accueil
                        </a>
                        <a href="{{ route('ins') }}"
                        class="nav-btn {{ request()->routeIs('inscription') ? 'active' : '' }} bg-white text-pink-600 px-4 py-2 rounded-full font-medium flex items-center">
                            📝 Inscription
                        </a>
                        <a href="{{ route('paiement') }}"
                        class="nav-btn bg-white text-pink-600 px-4 py-2 rounded-full font-medium flex items-center">
                            📝 Paiement inscription
                        </a>
                        <a href="#"
                        class="nav-btn bg-white text-pink-600 px-4 py-2 rounded-full font-medium flex items-center">
                            🎓 Formations
                        </a>
                        {{-- <a href="#"
                        class="nav-btn bg-white text-pink-600 px-4 py-2 rounded-full font-medium flex items-center">
                            👨‍🏫 Formateurs
                        </a> --}}
                        @if (auth()->check())
                        <a href="{{ route('dash') }}"
                        class="nav-btn bg-[var(--secondary-gold)] text-pink-700 px-4 py-2 rounded-full font-medium flex items-center">
                            🔐 Retourne sur le tableau de bord
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                        class="nav-btn bg-[var(--secondary-gold)] text-pink-700 px-4 py-2 rounded-full font-medium flex items-center">
                            🔐 Connexion
                        </a>
                        @endif
                        
                    </nav>
                </div>

                <!-- Barre de séparation décorative -->
                <div class="decoration-bar my-4"></div>

                <!-- Bannière texte défilant -->
                <div class="scrolling-banner mt-4">
                    <div class="scrolling-text">
                        <span>🌟 Former des professionnelles compétentes dans les soins du visage, du corps et la beauté des mains et pieds. 🌟</span>
                        <span>💖 Transformez votre passion en carrière 💖</span>
                        <span>✨ Favoriser l’insertion professionnelle à travers des stages encadrés et une pédagogie active. ✨</span>
                        <span>🎓 Développer le savoir-faire pratique et théorique nécessaire à l’exercice du métier d’esthéticienne. 🎓</span>
                        <span>💫 Apprentissage pratique et théorique 💫</span>
                    </div>
                </div>
            </div>
        </header>


        @yield('content')


        <!-- Footer -->
        <footer class="bg-gray-900 text-white pt-12 pb-6">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-xl font-bold mb-4 text-pink-400">CAP Esthétique</h3>
                        <p class="mb-4">Plateforme de formation du Ministère de l'Enseignement Technique et Professionnel.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-pink-400 hover:text-pink-300"><i data-feather="facebook"></i></a>
                            <a href="#" class="text-pink-400 hover:text-pink-300"><i data-feather="instagram"></i></a>
                            <a href="#" class="text-pink-400 hover:text-pink-300"><i data-feather="youtube"></i></a>
                        </div>
                    </div>
                    
                    <!-- Links -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Formation</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-pink-300">Programme CAP</a></li>
                            <li><a href="#" class="hover:text-pink-300">Modules formation</a></li>
                            <li><a href="#" class="hover:text-pink-300">Inscription</a></li>
                            <li><a href="#" class="hover:text-pink-300">Témoignages</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Contact</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i data-feather="phone" class="mr-2 w-4 h-4 mt-1 text-pink-400"></i>
                                <span>+242 06 823 90 29</span>
                            </li>
                            <li class="flex items-start">
                                <i data-feather="mail" class="mr-2 w-4 h-4 mt-1 text-pink-400"></i>
                                <span>contact@beautybloom.cg</span>
                            </li>
                            <li class="flex items-start">
                                <i data-feather="map-pin" class="mr-2 w-4 h-4 mt-1 text-pink-400"></i>
                                <span>Bacongo, Brazzaville</span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Newsletter -->
                    <div>
                        <h3 class="text-lg font-bold mb-4">Newsletter</h3>
                        <p class="mb-4">Abonnez-vous pour recevoir nos actualités.</p>
                        <form class="flex">
                            <input type="email" placeholder="Votre email" class="bg-gray-800 text-white px-4 py-2 rounded-l focus:outline-none focus:ring-2 focus:ring-pink-400 w-full">
                            <button type="submit" class="bg-pink-600 hover:bg-pink-500 px-4 py-2 rounded-r">
                                <i data-feather="send"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center">
                    <p>&copy; 2023 CAP Esthétique. Tous droits réservés.</p>
                    <p class="mt-2 md:mt-0">Développé par <span class="text-pink-400">DEV TECH</span></p>
                </div>
            </div>
        </footer>
    </div>


    @include('sweetalert::alert')
    @include('sweetalert2::index')
    @livewireScripts()
    @vite('resources/js/app.js')
    @stack('scripts')
    <script>
  (function () {
    const rail = document.getElementById('rail');
    const slides = Array.from(rail.children);
    const prev = document.getElementById('prevBtn');
    const next = document.getElementById('nextBtn');
    const dotsWrap = document.getElementById('dots');

    // Création des indicateurs
    const dots = slides.map((_, i) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'w-2.5 h-2.5 rounded-full bg-gray-300 hover:bg-gray-400 transition';
      b.setAttribute('aria-label', 'Aller à la diapositive ' + (i + 1));
      b.addEventListener('click', () => goTo(i));
      dotsWrap.appendChild(b);
      return b;
    });

    let index = 0;
    let autoplayMs = 3500;
    let timer = null;

    function itemWidth() {
      // largeur du slide + gap estimée via getBoundingClientRect()
      const s = slides[0];
      if (!s) return 0;
      const w = s.getBoundingClientRect().width;
      // gap = 0 si 1er, sinon delta entre 2
      if (slides.length > 1) {
        const b0 = slides[0].getBoundingClientRect();
        const b1 = slides[1].getBoundingClientRect();
        return b1.left - b0.left;
      }
      return w;
    }

    function updateDots() {
      dots.forEach((d, i) => {
        d.className = 'w-2.5 h-2.5 rounded-full transition ' + (i === index ? 'bg-pink-600' : 'bg-gray-300 hover:bg-gray-400');
      });
    }

    function goTo(i) {
      index = (i + slides.length) % slides.length;
      rail.scrollTo({ left: index * itemWidth(), behavior: 'smooth' });
      updateDots();
      restartAutoplay();
    }

    function nextSlide() { goTo(index + 1); }
    function prevSlide() { goTo(index - 1); }

    prev.addEventListener('click', prevSlide);
    next.addEventListener('click', nextSlide);

    // Auto défilement
    function startAutoplay() {
      stopAutoplay();
      timer = setInterval(nextSlide, autoplayMs);
    }
    function stopAutoplay() { if (timer) clearInterval(timer); }
    function restartAutoplay() { stopAutoplay(); startAutoplay(); }

    // Pause au survol
    rail.addEventListener('mouseenter', stopAutoplay);
    rail.addEventListener('mouseleave', startAutoplay);

    // Synchro lors d'un scroll manuel (snap)
    let raf = null;
    rail.addEventListener('scroll', () => {
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        const w = itemWidth();
        if (w > 0) {
          index = Math.round(rail.scrollLeft / w);
          updateDots();
        }
      });
    });

    // Ajuste au resize
    window.addEventListener('resize', () => {
      // réaligne sur le bon index après un resize
      rail.scrollTo({ left: index * itemWidth(), behavior: 'instant' in window ? 'instant' : 'auto' });
    });

    // Init
    updateDots();
    startAutoplay();
  })();
</script>
    <script>
        feather.replace();
        
        // Initialize Vanta.js
        VANTA.GLOBE({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0xFF5E8A,
            size: 0.80,
            backgroundColor: 0xF8FAFC
        });
        
        // Scrolling text animation
        document.addEventListener('DOMContentLoaded', function() {
            const scrollingElements = document.querySelectorAll('.scrolling-text span');
            scrollingElements.forEach(el => {
                el.style.animationDuration = `${20 + Math.random() * 20}s`;
            });
        });
    </script>
    <!-- Inclure Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
   

    

</body>
</html>
