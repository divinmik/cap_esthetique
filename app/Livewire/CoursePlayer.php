<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CoursePlayer extends Component
{
    public array $videos = [
        [
            'title' => 'Présentation',
            'description' => 'Présentation cap esthétique',
            'duration' => '22:00',
            'src' => 'assets/videos/presentation_formation.mp4',
        ],
        [
            'title' => 'Techniques de massage facial avancé',
            'description' => 'Module: Soins du visage • Formatrice: Mme Diallo',
            'duration' => '25:00',
            'src' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
        ],
        [
            'title' => 'Maquillage de jour professionnel',
            'description' => 'Module: Maquillage • Formatrice: Mme Diallo',
            'duration' => '18:00',
            'src' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
        ],
        [
            'title' => 'Épilation à la cire chaude',
            'description' => 'Module: Épilation • Formatrice: M. Kimbembe',
            'duration' => '32:00',
            'src' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
        ],
        [
            'title' => 'Soins des mains et manucure',
            'description' => 'Module: Soins mains • Formatrice: Mme Diallo',
            'duration' => '22:00',
            'src' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
        ],
        [
            'title' => 'Soins des mains et manucure',
            'description' => 'Module: Soins mains • Formatrice: Mme Diallo',
            'duration' => '22:00',
            'src' => 'assets/videos/presentation_formation.mp4',
        ],
    ];

    public int $currentIndex = 0;

    public function select(int $index): void
    {
        $this->currentIndex = $index;
        $this->dispatch('load-video'); // pour que l’Alpine recharge la vidéo
       
    }

    public function render()
    {
        return view('livewire.course-player');
    }
}
