<div
    x-data="coursePlayer({{ \Illuminate\Support\Js::from($videos) }}, @entangle('currentIndex'))"
    x-init="init()"
    x-on:load-video.window="loadCurrent()"
>
    <x-slot name="title_page">
        {{ $title }}
    </x-slot>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Proposé par les professeurs</h4>
        <a href="{{ route('cand.modules.index') }}" class="btn btn-outline-secondary">← Retour</a>
    </div>

    @if(empty($videos))
        <div class="alert alert-info">Aucune vidéo pour ce module. Consultez les ressources à droite.</div>
    @endif

    <div class="container my-4">
        <div class="row g-4">
            {{-- Colonne principale : LECTEUR VIDÉO --}}
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="bg-black" wire:ignore>
                        <div class="ratio ratio-16x9 position-relative">
                            {{-- VIDEO TOUJOURS PRÉSENTE + AU-DESSUS (z-index) --}}
                            <video
                                x-ref="video"
                                class="position-absolute top-0 start-0 w-100 h-100"
                                style="z-index:2; object-fit:contain; background:#000;"
                                controls
                                playsinline
                                muted
                                preload="auto"
                                @timeupdate="onTimeUpdate"
                                @loadedmetadata="onLoaded"
                                @ended="onEnded"
                            ></video>

                            {{-- Placeholder visuel (ne bloque pas les clics) --}}
                            <div
                                x-show="!loaded"
                                class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white text-center p-4"
                                style="z-index:1; pointer-events:none; background:rgba(0,0,0,.2);"
                            >
                                {{-- <div class="display-6 mb-2">📹</div>
                                <small class="opacity-75">Chargement de la vidéo…</small> --}}
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <h2 class="h4 fw-semibold mb-1" x-text="current()?.title ?? 'Aucune vidéo sélectionnée'"></h2>
                        <p class="text-muted mb-3" x-text="current()?.description ?? 'Choisissez une vidéo dans la liste'"></p>

                        {{-- Progression --}}
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

                        {{-- Contrôles --}}
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" @click="playPause" class="btn btn-primary">
                                <span x-text="playing ? '⏸️ Pause' : '▶️ Lecture'">▶️ Lecture</span>
                            </button>

                            <button type="button" @click="restart" class="btn btn-outline-secondary">⏮️ Recommencer</button>

                            <div class="btn-group" role="group" aria-label="Skip group">
                                <button type="button" @click="skip(-10)" class="btn btn-outline-secondary">⏪ -10s</button>
                                <button type="button" @click="skip(10)"  class="btn btn-outline-secondary">⏩ +10s</button>
                            </div>

                            <button type="button" @click="toggleFullscreen" class="btn btn-outline-secondary">⛶ Plein écran</button>
                            <button type="button" @click="toggleSpeed" class="btn btn-outline-secondary">
                                <span x-text="speedLabel">1.0x</span>
                            </button>
                        </div>

                        <div class="form-text mt-3">
                            Astuce : <kbd>J</kbd>/<kbd>L</kbd> = reculer/avancer, <kbd>K</kbd> = pause/lecture.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar : RESSOURCES + PLAYLIST --}}
            <aside class="col-lg-4">
                <div class="sticky-top" style="top: 1rem;">
                    {{-- Ressources --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h6 mb-3">📎 Ressources associées</h3>
                            @php
                                $pdfs   = array_values(array_filter($resources, fn($r) => ($r['type'] ?? '') === 'pdf'));
                                $images = array_values(array_filter($resources, fn($r) => ($r['type'] ?? '') === 'image'));
                            @endphp

                            @forelse ($pdfs as $res)
                                <a href="{{ $res['url'] }}" download
                                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 rounded-3 mb-2 border">
                                    <span class="fs-4">📄</span>
                                    <div class="flex-fill">
                                        <strong class="d-block">{{ $res['title'] }}</strong>
                                        <small class="text-muted">PDF @if(!empty($res['size_label'])) • {{ $res['size_label'] }} @endif</small>
                                    </div>
                                    <span class="badge bg-light text-dark">Télécharger</span>
                                </a>
                            @empty
                                <div class="text-muted small mb-2">Aucun PDF.</div>
                            @endforelse

                            @forelse ($images as $res)
                                <a href="{{ $res['url'] }}" target="_blank"
                                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 rounded-3 mb-2 border">
                                    <span class="fs-4">🖼️</span>
                                    <div class="flex-fill">
                                        <strong class="d-block">{{ $res['title'] }}</strong>
                                        <small class="text-muted">Image @if(!empty($res['size_label'])) • {{ $res['size_label'] }} @endif</small>
                                    </div>
                                    <span class="badge bg-light text-dark">Ouvrir</span>
                                </a>
                            @empty
                                <div class="text-muted small">Aucune image.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Playlist vidéos --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="h6 mb-3">📋 Cours (vidéos)</h3>

                            @if(empty($videos))
                                <div class="text-muted small">Aucune vidéo disponible pour ce module.</div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach ($videos as $i => $v)
                                        <button
                                            type="button"
                                            wire:click="select({{ $i }})"
                                            class="list-group-item list-group-item-action rounded-3 mb-2 border
                                                @if($i === $currentIndex) active text-white @endif"
                                            style="@if($i === $currentIndex) background:#0d6efd;border-color:#0d6efd; @endif"
                                        >
                                            <div class="d-flex w-100 justify-content-between">
                                                <strong class="mb-1">{{ $v['title'] }}</strong>
                                                <small class="@if($i === $currentIndex) text-white-50 @else text-muted @endif">
                                                    {{ $v['duration'] ?? '' }}
                                                </small>
                                            </div>
                                            <small class="@if($i === $currentIndex) text-white-50 @else text-muted @endif">
                                                {{ \Illuminate\Support\Str::of($v['description'] ?? 'Vidéo du cours')->before(' •') }}
                                            </small>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

@push('scripts')
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
    _loadToken: 0,

    init() {
      this.$nextTick(() => this.loadCurrent());
      window.addEventListener('load-video', () => this.loadCurrent());
      this.$watch('currentIndexEntangle', () => this.loadCurrent());
    },

    current() {
      return this.videos?.[this.currentIndexEntangle] ?? null;
    },

    // ----- CHARGEMENT ROBUSTE + GESTION Abort/Autoplay -----
    loadCurrent() {
      const item = this.current();
      const v = this.$refs.video;
      if (!v || !item?.src) {
        this.loaded = false; this.playing = false; return;
      }

      this._loadToken = (this._loadToken || 0) + 1;
      const token = this._loadToken;

      this.playing = false;
      this.loaded  = false;
      this.currentTime = 0;
      this.duration    = 0;
      this.progress    = 0;

      try {
        v.pause();
        v.removeAttribute('src');
        v.load();

        v.src = item.src;
        v.playbackRate = this.playbackRate;

        this.once(v, 'loadedmetadata', () => {
          if (token !== this._loadToken) return;
          this.duration = isNaN(v.duration) ? 0 : v.duration;
          this.updateLabels();
        });

        this.once(v, 'canplay', async () => {
          if (token !== this._loadToken) return;
          this.loaded = true;
          await this.playSafely(v); // gère AbortError & NotAllowedError
        });

        v.onerror = () => {
          if (token !== this._loadToken) return;
          this.loaded = false;
          this.toast("⚠️ Impossible de charger cette vidéo.");
        };
      } catch (e) {
        this.loaded = false;
        this.toast("⚠️ Erreur lors du chargement.");
      }
    },

    // one-shot listener
    once(el, event, handler) {
      const h = (e) => { el.removeEventListener(event, h); handler(e); };
      el.addEventListener(event, h, { passive: true });
    },

    // play() safe: ignore AbortError, handle NotAllowedError
    async playSafely(video) {
      try {
        const p = video.play();
        if (p && typeof p.then === 'function') { await p; }
        this.playing = true;
      } catch (err) {
        const name = err?.name || '';
        if (name === 'AbortError') return; // changement de src: normal
        if (name === 'NotAllowedError') {
          // Autoplay bloqué: attendre un clic utilisateur
          this.playing = false;
          return;
        }
        console.warn('video.play() error:', err);
        this.playing = false;
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

    onEnded() { this.toast('🎉 Félicitations ! Vous avez terminé cette vidéo.'); },

    playPause() {
      const v = this.$refs.video;
      if (!v) return;
      if (v.paused) { this.playSafely(v); }
      else { v.pause(); this.playing = false; }
    },

    restart() {
      const v = this.$refs.video;
      if (!v) return;
      v.currentTime = 0;
      this.playSafely(v);
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

    get speedLabel() { return `${this.playbackRate.toFixed(2).replace(/\.00$/, '.0')}x`; },

    toggleFullscreen() {
      const v = this.$refs.video;
      if (!document.fullscreenElement) { v?.requestFullscreen?.().catch(() => {}); }
      else { document.exitFullscreen?.(); }
    },

    formatTime(s) {
      if (s == null || isNaN(s)) return '00:00';
      const m = Math.floor(s / 60), sec = Math.floor(s % 60);
      return `${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
    },

    updateLabels() {
      this.progress = this.duration ? (this.currentTime / this.duration) * 100 : 0;
    },

    openQuiz() { this.toast('📝 Ouverture du quiz…'); },
    showComplementary() { this.toast('🎬 Chargement de la vidéo complémentaire…'); },

    toast(msg) { alert(msg); },
  }))
})
</script>
@endpush
