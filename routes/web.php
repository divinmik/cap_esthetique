<?php

use App\Livewire\Messagerie;
use App\Mail\MailInfoCompte;
use App\Models\ModuleContent;
use App\Livewire\CoursePlayer;
use App\Models\Helper_function;
use App\Livewire\Candidat\Profil;
use App\Livewire\Sectiondash\Dash;
use App\Livewire\Sectiondash\Prof;
use App\Livewire\Sectiondash\Liste;
use App\Services\MobileMoneyService;
use App\Livewire\Candidat\Facturepay;
use App\Livewire\Sectiondash\Facture;
use App\Livewire\Sectiondash\Profdoc;
use Illuminate\Support\Facades\Route;
use App\Livewire\Candidat\ModulesShow;
use App\Livewire\Gestionprof\Download;
use App\Livewire\Candidat\CandidatFile;
use App\Livewire\Candidat\CandidatQuiz;
use App\Livewire\Candidat\ModulesIndex;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Livewire\Prof\Manager as ProfManager;
use App\Livewire\Sectiondash\Modulecours;
use App\Livewire\Sectiondash\Utilisateur;
use App\Livewire\Candidat\CandidatFacture;
use App\Livewire\Candidates\PersonnelFile;
use App\Livewire\Gestionprof\Modulegestion;
use App\Livewire\Sectiondash\Candidatliste;
use App\Livewire\Facture\ManagerFacturation;
use App\Livewire\Candidat\Manager as CandidatsManager;









Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/inscription', function () {
    return view('pages.page_inscription');
})->name('ins');

Route::get('/payment-inscription/{code?}', function ($code=null) {
    return view('pages.page_paiement',["code"=>$code]);
})->name('paiement');

Route::get('/listes/{code}', function () {
    return view('pages.page_liste');
})->name('listes');

Route::get('/file_pdf', function () {
    
    return view('pdf.file_pdf');
});

Route::get('/testmail', function () {
    $mailData = [
            'title' => 'Création de compte crée avec succès',
            'email'=>"mikdivin@gmail.com",
            'fullname' => "mikangamani nsimba ",
            'pwd' => 'secret',

        ];

    Helper_function::send_mail(new MailInfoCompte($mailData),"mikdivin@gmail.com");
    return "all";
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
    Route::get('facture_gest',ManagerFacturation::class)->name('facture');
    Route::get('users_accompte',Utilisateur::class)->name('user_create');
    Route::get('module_gest',Modulecours::class)->name('module_info');

    Route::get('/file/{path}', function($path){
        // Empêche les ../ pour sécurité
        $path = str_replace('..', '', $path);

        // Vérifie que le fichier existe dans le disk public
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // Retourne le fichier (stream, pas de copie mémoire)
        return Storage::disk('public')->response($path);
    })->where('path', '.*')->name('file.show');

    //streaming
    Route::get('/cours', CoursePlayer::class)->name('cours');
    
    //streaming
    Route::get('/messages', Messagerie::class)->name('message');

    //candidat route
    Route::prefix('school')
    ->as('cand.')
    ->group(function(){
        Route::get('profil',Profil::class)->name('profil');
        Route::get('facture_cand',Facturepay::class)->name('invoice');
        Route::get('candidats', CandidatsManager::class)->name('candidats.manager');
        Route::get('/modules', ModulesIndex::class)->name('modules.index'); // accueil cartes
        Route::get('/modules/{code}', ModulesShow::class)->name('modules.show'); // détail par titre
        Route::get('candidats/{code?}', CandidatFile::class)->name('filecand');
        Route::get('quiz', CandidatQuiz::class)->name('quizs');
        Route::get('factures', CandidatFacture::class)->name('facture_cand');
    });
    
    //candidat route
    Route::prefix('professeur')
    ->as('pr.')
    ->group(function(){
         Route::get('create_module',Modulegestion::class)->name('module_create');
         Route::get('download_module',Download::class)->name('module_down');
          // page du manager prof par code
        Route::get('/prof/{code}', ProfManager::class)->name('manager');

        // route de téléchargement signée (optionnelle si tu veux contrôler l’accès)
        Route::get('/download/{content}', function (ModuleContent $content) {
            abort_unless($content->isFile(), 404);
            return response()->download(storage_path('app/public/'.$content->path));
        })->name('content.download');

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
