<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\PaiementStatut;
use Illuminate\Support\Facades\Schedule;



Schedule::command(PaiementStatut::class)->everyMinute();

/* Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
 */