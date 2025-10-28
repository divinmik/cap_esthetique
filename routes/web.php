<?php

use App\Livewire\Candidat\Profil;
use App\Livewire\Sectiondash\Dash;
use App\Livewire\Sectiondash\Prof;
use App\Livewire\Sectiondash\Liste;
use App\Services\MobileMoneyService;
use App\Livewire\Sectiondash\Facture;
use App\Livewire\Sectiondash\Profdoc;
use Illuminate\Support\Facades\Route;
use App\Livewire\Gestionprof\Download;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Livewire\Sectiondash\Modulecours;
use App\Livewire\Sectiondash\Utilisateur;
use App\Livewire\Gestionprof\Modulegestion;
use App\Livewire\Sectiondash\Candidatliste;













Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/inscription', function () {
    return view('pages.page_inscription');
})->name('ins');

Route::get('/payment-inscription', function () {
    return view('pages.page_paiement');
})->name('paiement');

Route::get('/listes/{code}', function () {
    return view('pages.page_liste');
})->name('listes');

Route::get('/file_pdf', function () {
    
    return view('pdf.file_pdf');
});

Route::get('/collect/{transaction}', function ($transaction) {
    $api = new MobileMoneyService();
    /*$statut = $api->collect([
         'external_ref' => "test pin",
            'amount' => "1",
            'currency' => 'XAF',
            'payer_phone' => "242068409872",
            'description' => 'Paiement 001',
    ]);*/
    $statut = $api->verify($transaction);
    dd($statut);
});

Route::get('/docs/{filename}', function ($filename) {
    // Chemin complet de l'image dans le répertoire storage
    $path = 'profils/'. $filename;
    // Vérifiez si le fichier existe
    
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    // Obtenez le contenu du fichier
    $file = Storage::disk('public')->get($path);
    // Obtenez le type de fichier MIME
    $type = Storage::disk('public')->mimeType($path);
    
    //dd($filename,$file,Response::make($file, 200));
    // Retournez la réponse avec le contenu du fichier et le type MIME
    return Response::make($file, 200)
        ->header("Content-Type", $type);
})->name('docs.display');


Route::middleware([
    'auth:sanctum',
])->group(function () {
    
    Route::get('dashboard',Dash::class)->name('dash');
    Route::get('create_prof',Prof::class)->name('createprof');
    Route::get('doc_prof/{code}',Profdoc::class)->name('docprof');
    Route::get('gest_candidat',Candidatliste::class)->name('cand_ges');
    Route::get('facture_gest',Facture::class)->name('facture');
    Route::get('users_accompte',Utilisateur::class)->name('user_create');
    Route::get('module_gest',Modulecours::class)->name('module_info');

    //candidat route
    Route::prefix('school')
    ->as('cand.')
    ->group(function(){
         Route::get('profil',Profil::class)->name('profil');
    });
    
    //candidat route
    Route::prefix('professeur')
    ->as('pr.')
    ->group(function(){
         Route::get('create_module',Modulegestion::class)->name('module_create');
         Route::get('download_module',Download::class)->name('module_down');

    });
    

});
/* 
Route::get('partnership',Gestionpartenaire::class)->name('partnership');
 */
/* Gestion ecole */
/* Route::prefix('school')
->as('sc.')
->group(function(){
    Route::get('document/{code}',Docs::class)->name('doc');
    Route::get('gestion',Gestionecole::class)->name('gestions');
    Route::get('children',Gestionenfant::class)->name('enfant');
    Route::get('childrenlevel',Gestionniveau::class)->name('level');
    Route::get('level',Setingniveau::class)->name('level_st');
}); */
/*
fin
*/
