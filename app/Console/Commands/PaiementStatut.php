<?php

namespace App\Console\Commands;

use App\Models\Inscription;
use Illuminate\Support\Str;
use App\Models\Momopaiement;
use App\Models\Helper_function;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\MobileMoneyService;

class PaiementStatut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:paiement-statut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'momo mtn statut paiement client';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //vérification statut momo pending
        $momos = Momopaiement::where('status',"PENDING")->get();

        if(!empty($momos)){
            foreach ($momos as $key => $value) {

                $api  = new MobileMoneyService();
                $resp = $api->verify($value->transaction_id);
            // dd($resp['success'],$transactionID,$resp);
                $paiement = Momopaiement::where('transaction_id',$value->transaction_id)->first();
                Log::info("Transaction Momo ".$value->transaction_id);
                if (!empty($resp['success']) && !empty($resp['data']) && $paiement) {
                    $status = strtoupper($resp['data']['status'] ?? '');

                    if ($status === 'SUCCESS') {
                        
                        if ($paiement && ($paiement->payment_status ?? null) !== 'paid') {
                            
                        $resultat =   Momopaiement::inscriptionConfirme($paiement->inscription_id);
                            
                            $paiement->status = 'SUCCESS';
                            $paiement->save();

                            //send sms
                        }
                    }

                    if (in_array($status, ['FAILED', 'EXPIRED'], true)) {                      

                        $paiement->status = 'FAILED';
                        $paiement->save();
                    }

                }
                
            Log::info("Succes momo statut fermeture ".$value->transaction_id);
            }
        }
        Log::info("Pas de paiement momo en attente ".date('d'));
    }
}
