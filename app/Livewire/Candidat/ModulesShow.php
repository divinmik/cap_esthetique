<?php
// app/Livewire/Candidat/ModulesShow.php

namespace App\Livewire\Candidat;

use Livewire\Component;
use App\Models\CourseModule;
use Illuminate\Support\Facades\Storage;

class ModulesShow extends Component
{
    /** Paramètre route : code du module (ex: "MOD-CAP-ACC-HYG") */
    public string $code;

    /** Titre affiché (issu du premier module trouvé avec ce code) */
    public string $title = '';

    /** Playlist vidéo pour Alpine (gauche) */
    public array $videos = [];

    /** Ressources (PDF + images) pour la sidebar (droite) */
    public array $resources = [];

    /** Index courant dans la playlist vidéo */
    public int $currentIndex = 0;

    /** Optionnel pour d'autres UI (non utilisé ici) */
    public ?int $openModuleId = null;

    public function select(int $index): void
    {
        if (! isset($this->videos[$index])) return;
        $this->currentIndex = $index;
        $this->dispatch('load-video'); // Alpine rechargera la vidéo
    }

    public function mount(string $code): void
    {
        $this->code = $code;

        // Récupérer tous les modules (potentiellement de profs différents) portant ce code
        $modules = CourseModule::with(['user', 'contents'])
            ->where('code', $this->code)
            ->orderByDesc('created_at')
            ->get();

        if ($modules->isEmpty()) {
            // Rien trouvé: on affiche un titre fallback et on sort proprement
            $this->title = 'Module introuvable';
            $this->videos = [];
            $this->resources = [];
            $this->currentIndex = 0;
            return;
        }

        // Titre d’affichage (on prend celui du premier)
        $this->title = (string) $modules->first()->title;

        // Aplatir tous les contenus de tous les modules trouvés
        $allContents = $modules->flatMap->contents;

        // ---- VIDEOS (video_file + video_url)
        $this->videos = $allContents
            ->whereIn('type', ['video_file', 'video_url'])
            ->map(function ($c) {
                $src = $c->type === 'video_url'
                    ? $c->url
                    : ($c->path ? route('file.show', $c->path) : null);

                return [
                    'title'       => $c->title,
                    'description' => optional($c->module)->description ?? 'Vidéo du cours',
                    'duration'    => null, 
                    'src'         => $src,
                ];
            })
            ->filter(fn ($v) => !empty($v['src']))
            ->values()
            ->all();

        // ---- RESSOURCES (pdf + image)
        $this->resources = $allContents
            ->whereIn('type', ['pdf', 'image'])
            ->map(function ($c) {
                return [
                    'title'      => $c->title,
                    'type'       => $c->type, // 'pdf' | 'image'
                    'url'        => $c->path ? route('file.show', $c->path): null,
                    'size_label' => $c->size_bytes ? number_format($c->size_bytes / 1024 / 1024, 2) . ' MB' : null,
                ];
            })
            ->filter(fn ($r) => !empty($r['url']))
            ->values()
            ->all();
          
        // Sélection initiale
        $this->currentIndex = empty($this->videos) ? 0 : 0;
    }

    public function render()
    {
        return view('livewire.candidat.modules-show', [
            'title'        => $this->title,
            'videos'       => $this->videos,
            'resources'    => $this->resources,
            'currentIndex' => $this->currentIndex,
        ]);
    }
}
