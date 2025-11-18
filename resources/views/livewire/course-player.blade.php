<div
    x-data="coursePlayer({{ \Illuminate\Support\Js::from($videos) }}, @entangle('currentIndex'))"
    x-init="init()"
    x-on:load-video.window="loadCurrent()"
    class="container mx-auto p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6"
>
    <x-slot name="title_page">
        Module gestion
    </x-slot>

    <div class="container my-4">
        <div class="row g-4">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <!-- Cadre vidéo -->
                    <div class="bg-black">
                        <div class="ratio ratio-16x9 position-relative">
                            <video
                                x-ref="video"
                                class="w-100 h-100"
                                controls
                                playsinline
                                muted
                                x-show="loaded"
                                @timeupdate="onTimeUpdate"
                                @loadedmetadata="onLoaded"
                                @ended="onEnded"
                            ></video>

                            <!-- Placeholder quand aucune vidéo n’est chargée -->
                            {{-- <div x-show="!loaded"
                                 class="d-flex flex-column align-items-center justify-content-center text-white text-center p-4">
                                <div class="display-6 mb-2">📹</div>
                                <small class="opacity-75">Sélectionnez une vidéo à lire</small>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Infos + Progression + Contrôles -->
                    <div class="card-body">
                        <h2 class="h4 fw-semibold mb-1" x-text="current()?.title ?? 'Aucune vidéo sélectionnée'"></h2>
                        <p class="text-muted mb-3" x-text="current()?.description ?? 'Choisissez une vidéo dans la liste'"></p>

                        <!-- Progression -->
                        <div class="mb-3">
                            <div class="progress rounded-pill" style="height: 8px;">
                                <div class="progress-bar" role="progressbar"
                                     :style="`width:${progress}%`"
                                     :aria-valuenow="Math.round(progress)"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mt-2">
                                <span x-text="formatTime(currentTime)">00:00</span>
                                <span x-text="formatTime(duration)">00:00</span>
                            </div>
                        </div>

                        <!-- Contrôles -->
                        <div class="d-flex flex-wrap gap-2">
                            <button @click="playPause" class="btn btn-primary">
                                <span x-text="playing ? '⏸️ Pause' : '▶️ Lecture'">▶️ Lecture</span>
                            </button>

                            <button @click="restart" class="btn btn-outline-secondary">⏮️ Recommencer</button>

                            <div class="btn-group" role="group" aria-label="Skip group">
                                <button @click="skip(-10)" class="btn btn-outline-secondary">⏪ -10s</button>
                                <button @click="skip(10)"  class="btn btn-outline-secondary">⏩ +10s</button>
                            </div>

                            <button @click="toggleFullscreen" class="btn btn-outline-secondary">⛶ Plein écran</button>
                            <button @click="toggleSpeed" class="btn btn-outline-secondary">
                                <span x-text="speedLabel">1.0x</span>
                            </button>
                        </div>

                        <div class="form-text mt-3">
                            Astuce : <kbd>J</kbd>/<kbd>L</kbd> = reculer/avancer, <kbd>K</kbd> = pause/lecture.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar à droite (Ressources + Cours) -->
            <aside class="col-lg-4">
                <div class="sticky-top" style="top: 1rem;">
                    <!-- Ressources -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h6 mb-3">📎 Ressources associées</h3>

                            <a href="{{ asset('pdf/fiche_massage.pdf') }}" download
                               class="list-group-item list-group-item-action d-flex align-items-center gap-3 rounded-3 mb-2 border">
                                <span class="fs-4">📄</span>
                                <div class="flex-fill">
                                    <strong class="d-block">Fiche technique massage</strong>
                                    <small class="text-muted">PDF • 2.3 MB</small>
                                </div>
                                <span class="badge bg-light text-dark">Télécharger</span>
                            </a>

                            <button type="button" @click="openQuiz"
                                    class="list-group-item list-group-item-action d-flex align-items-center gap-3 rounded-3 mb-2 border bg-white">
                                <span class="fs-4">📊</span>
                                <div class="text-start">
                                    <strong class="d-block">Quiz évaluation</strong>
                                    <small class="text-muted">10 questions • 15 min</small>
                                </div>
                            </button>

                            <button type="button" @click="showComplementary"
                                    class="list-group-item list-group-item-action d-flex align-items-center gap-3 border rounded-3 bg-white">
                                <span class="fs-4">🎬</span>
                                <div class="text-start">
                                    <strong class="d-block">Vidéo complémentaire</strong>
                                    <small class="text-muted">8 minutes • Démonstration</small>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Cours -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="h6 mb-3">📋 Cours disponibles</h3>

                            <div class="list-group list-group-flush">
                                @foreach ($videos as $i => $v)
                                    <button
                                        wire:click="select({{ $i }})"
                                        class="list-group-item list-group-item-action rounded-3 mb-2 border
                                            @if($i === $currentIndex) active text-white @endif"
                                        style="@if($i === $currentIndex) background:#0d6efd;border-color:#0d6efd; @endif"
                                    >
                                        <div class="d-flex w-100 justify-content-between">
                                            <strong class="mb-1">{{ $v['title'] }}</strong>
                                            <small class="@if($i === $currentIndex) text-white-50 @else text-muted @endif">
                                                {{ $v['duration'] }}
                                            </small>
                                        </div>
                                        <small class="@if($i === $currentIndex) text-white-50 @else text-muted @endif">
                                            {{ \Illuminate\Support\Str::of($v['description'])->before(' •') }}
                                        </small>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- Alpine component --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('coursePlayer', (videos, currentIndexEntangle) => ({
                videos,
                currentIndexEntangle,
                loaded: false,
                playing: false,
                playbackRate: 1.0,
                currentTime: 0,
                duration: 0,
                progress: 0,

                init() {
                    // Charger au démarrage
                    this.$nextTick(() => this.loadCurrent());

                    // Écoute DOM (Livewire v3 envoie un event navigateur)
                    // -> géré aussi en HTML par x-on:load-video.window="loadCurrent()"
                    window.addEventListener('load-video', () => this.loadCurrent());

                    // Sécurité : recharger si l’index lié change (même sans event)
                    this.$watch('currentIndexEntangle', () => this.loadCurrent());
                },

                current() {
                    return this.videos?.[this.currentIndexEntangle] ?? null;
                },

                loadCurrent() {
                    const item = this.current();
                    const v = this.$refs.video;
                    if (!v || !item?.src) {
                        this.loaded = false;
                        this.playing = false;
                        return;
                    }

                    this.playing = false;
                    this.loaded  = false;
                    this.currentTime = 0;
                    this.duration    = 0;
                    this.progress    = 0;

                    try {
                        // Séquence fiable : pause -> retirer src -> load() -> remettre src -> load() -> play()
                        v.pause();
                        v.removeAttribute('src');
                        v.load();

                        v.src = item.src;
                        v.playbackRate = this.playbackRate;

                        v.onloadedmetadata = () => {
                            this.duration = isNaN(v.duration) ? 0 : v.duration;
                            this.loaded = true;
                            this.updateLabels();
                            v.play()
                                .then(() => { this.playing = true; })
                                .catch(() => { this.playing = false; });
                        };

                        v.onerror = () => {
                            this.toast("⚠️ Impossible de charger cette vidéo.");
                            this.loaded = false;
                        };
                    } catch (e) {
                        this.toast("⚠️ Erreur lors du chargement.");
                        this.loaded = false;
                    }
                },

                onLoaded() {
                    const v = this.$refs.video;
                    this.duration = isNaN(v.duration) ? 0 : v.duration;
                    this.updateLabels();
                },

                onTimeUpdate() {
                    const v = this.$refs.video;
                    this.currentTime = v.currentTime || 0;
                    this.duration    = v.duration || 0;
                    this.progress    = this.duration ? (this.currentTime / this.duration) * 100 : 0;
                },

                onEnded() {
                    this.toast('🎉 Félicitations ! Vous avez terminé cette vidéo.');
                },

                playPause() {
                    const v = this.$refs.video;
                    if (!v) return;
                    if (v.paused) { v.play(); this.playing = true; }
                    else { v.pause(); this.playing = false; }
                },

                restart() {
                    const v = this.$refs.video;
                    if (!v) return;
                    v.currentTime = 0; v.play(); this.playing = true;
                },

                skip(sec) {
                    const v = this.$refs.video;
                    if (!v) return;
                    const target = Math.min(Math.max(0, (v.currentTime || 0) + sec), v.duration || 0);
                    v.currentTime = target;
                },

                toggleSpeed() {
                    const speeds = [0.5, 0.75, 1.0, 1.25, 1.5, 2.0];
                    const idx = speeds.indexOf(this.playbackRate);
                    this.playbackRate = speeds[(idx + 1) % speeds.length];
                    if (this.$refs.video) this.$refs.video.playbackRate = this.playbackRate;
                },

                get speedLabel() {
                    return `${this.playbackRate.toFixed(2).replace(/\.00$/, '.0')}x`;
                },

                toggleFullscreen() {
                    const v = this.$refs.video;
                    if (!document.fullscreenElement) {
                        v?.requestFullscreen?.().catch(() => {});
                    } else {
                        document.exitFullscreen?.();
                    }
                },

                formatTime(s) {
                    if (s == null || isNaN(s)) return '00:00';
                    const m = Math.floor(s / 60);
                    const sec = Math.floor(s % 60);
                    return `${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
                },

                updateLabels() {
                    this.progress = this.duration ? (this.currentTime / this.duration) * 100 : 0;
                },

                openQuiz() {
                    this.toast('📝 Ouverture du quiz…');
                },

                showComplementary() {
                    this.toast('🎬 Chargement de la vidéo complémentaire…');
                },

                toast(msg) {
                    // Remplace par Swal.fire(...) si tu utilises SweetAlert
                    alert(msg);
                },
            }))
        })
    </script>
</div>
