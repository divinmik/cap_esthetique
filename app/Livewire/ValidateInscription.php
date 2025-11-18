<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inscription;
use Illuminate\Support\Str;
use App\Models\Momopaiement;
use App\Models\Helper_function;
use App\Services\MobileMoneyService;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

class ValidateInscription extends Component
{
    use WithSweetAlert;
    public $datas,$candidat,$code,$payer_phone,$currentUrl;
    public $transactionID;
    public $status = 'idle'; 
    public $elapsed = 0;
    public $timeout = 140;
    public $paiement; 
    public int $remaining = 140;   
    protected $pollInterval = 1; 
    public $polling = false;

    public function updating($k,$v){
        
        if($k == "code"){
            $this->candidat = Inscription::where('is_valide','0')
            ->where('code',$v)
            ->first();              
        }
        
        if($k == "payer_phone"){
            
        }
    }

    public function mount($code = null){
        $this->currentUrl = url()->current();
        if($code){
            $this->code = $code;
             $this->candidat = Inscription::where('is_valide','0')
            ->where('code',$this->code)
            ->first();
        }
        $this->datas = Inscription::where('is_valide','0')->get();
       
    }
    
    public function reset_data(){
        $this->payer_phone = "";
        $this->code = "";
        $this->candidat = "";
       
    }

    public function render()
    {
        return view('livewire.validate-inscription');
    }
    
    public function paiement_conf(): void
    {
    
        // Validation
        $this->validate(
            [
                'code'        => [
                    'required',
                    function ($attribute, $value, $fail) {
                    $r = Inscription::where('is_valide','0')
                        ->where('code',$value)
                        ->first();
                        if (empty($r)) {
                            return $fail(__('Code non reconnu'));
                        }
                    },
                ],
                'payer_phone' => [
                    'required',
                    'phone:CG',
                    function ($attribute, $value, $fail) {
                        // Normalise en E.164 (ex: +24206xxxxxxx)
                        $tel = Helper_function::phone($value);
                        // Après +242, les deux chiffres opérateur commencent à l'index 4
                    /*  $ope = substr($tel, 3, 2);
                        if ($ope !== '06') {
                            return $fail(__('Insérez un numéro MTN'));
                        } */
                    },
                ],
            ],
            [
                '*.required' => 'Les champs avec * sont obligatoires',
                '*.phone'    => 'Numéro de téléphone incorrect',
            ]
        );


            $api = new MobileMoneyService();

            // Normalise le numéro pour l’API
            $payerPhone = Helper_function::phone($this->payer_phone);

            // Appel d'initiation
            $resultat = $api->collect([
                'external_ref' => 'ref-' . Str::random(10),
                'amount'       => env('MONTANT_INITIAL'), // Montant fixe (XAF)
                'currency'     => 'XAF',
                'payer_phone'  => $payerPhone,
                'description'  => 'Paiement inscription CAP ESTHETIQUE',
            ]);

            // Vérif code retour
            if (!isset($resultat['status_code']) || (int)$resultat['status_code'] !== 201) {
                $this->dispatch('swal', [
                    'icon'  => 'error',
                    'title' => 'Merci de réessayer',
                    'text'  => 'Erreur interne lors de l’initialisation du paiement.',
                ]);
                return;
            }

            // Données API
            $data = $resultat['data'] ?? [];

            // Sécurise l'accès aux clés
            $transactionId = $data['transaction_id'] ?? null;
            if (!$transactionId) {
                $this->dispatch('swal', [
                    'icon'  => 'error',
                    'title' => 'Merci de réessayer',
                    'text'  => 'Réponse incomplète du prestataire (transaction_id manquant).',
                ]);
                return;
            }

            // Persistence (initialisation)
            $id_inscription = Momopaiement::create([
                'inscription_id' => $this->candidat->id,
                'nature'          => "inscription",
                'error'          => $data['error']         ?? null,
                'message'        => $data['message']       ?? null,
                'status'         => $data['status']        ?? null,
                'transaction_id' => $transactionId,
                'external_ref'   => $data['external_ref']  ?? null,
                'payment_url'    => $data['payment_url']   ?? null,
                'operator'       => $data['operator']      ?? 'MTN',
                'payer_phone'    => $data['payer_phone']   ?? $payerPhone,
            ]);

            // Démarre le polling
            $this->transactionID = $transactionId;
            $this->status        = strtoupper($data['status'] ?? 'PENDING');
            $this->polling       = true;
            $this->elapsed       = 0; // réinitialise le compteur
            
            // Ouvre l’alerte avec compteur (même canal que checkStatus)
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => 'paiement initialisé',
                'text'   => 'Veuillez valider le paiement sur votre téléphone. Composer *105# '
            ]);

            $this->reset_data();
            
            return;

        
    }


    /**
     * wire:poll.visible.1s="checkStatus"
     */
    public function checkStatus(): void
    {
        
        if (!$this->polling) {
            
            return;
        }

        // Démarrer l'alerte avec compteur au 1er tick
        if ($this->elapsed === 0) {
            $this->dispatch('swal', [
                'action' => 'start',
                'title'  => 'Vérification du paiement…',
                'text'   => 'Merci de patienter pendant la confirmation.',
                'timer'  => $this->timeout * 1000, // ms
            ]);
        }

        // Déjà confirmé ?
        if ($this->status === 'SUCCESS') {
            $this->polling = false;
            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'success',
                'title'  => 'Paiement réussi',
                'text'   => 'Votre règlement a été confirmé.',
                'stopTimer' => true,
            ]);
            return;
        }

        // Incrément du temps
        $this->elapsed += $this->pollInterval;

        try {
            $api  = new MobileMoneyService();
            $resp = $api->verify($this->transactionID);
           // dd($resp['success'],$this->transactionID,$resp);
            $this->paiement = Momopaiement::where('transaction_id',$this->transactionID)->first();
            if (!empty($resp['success']) && !empty($resp['data']) && $this->paiement) {
                $status = strtoupper($resp['data']['status'] ?? '');

                if ($status === 'SUCCESS') {
                    
                    if (property_exists($this, 'paiement') && $this->paiement && ($this->paiement->payment_status ?? null) !== 'paid') {
                        
                     $resultat =   Momopaiement::inscriptionConfirme($this->paiement->inscription_id);
                        
                        $this->paiement->status = 'SUCCESS';
                        $this->paiement->save();
                    }

                    $this->status  = 'SUCCESS';
                    $this->polling = false;

                    $this->dispatch('swal', [
                        'action' => 'status',
                        'icon'   => 'success',
                        'title'  => 'Paiement réussi',
                        'text'   => 'Votre règlement a été confirmé. votre mot de passe est: '.$resultat,
                        'stopTimer' => true,
                    ]);
                    return;
                }

                if (in_array($status, ['FAILED', 'EXPIRED'], true)) {
                    $this->status  = $status === 'EXPIRED' ? 'EXPIRED' : 'FAILURE';
                    $this->polling = false;

                    $this->paiement->status = 'FAILED';
                    $this->paiement->save();

                    $this->dispatch('swal', [
                        'action' => 'status',
                        'icon'   => 'error',
                        'title'  => $status === 'EXPIRED' ? 'Session expirée' : 'Paiement échoué',
                        'text'   => $status === 'EXPIRED'
                            ? 'La fenêtre de paiement est expirée. Veuillez réessayer.'
                            : 'Le paiement a été refusé par l’opérateur.',
                        'stopTimer' => true,
                    ]);
                    return;
                }
                // Sinon: PENDING/PROCESSING -> on continue à poller
            }

            // Timeout (même emplacement que ta logique d’origine : après l’appel)
            if ($this->elapsed >= $this->timeout) {
                $this->status  = 'TIMEOUT';
                $this->polling = false;

                $this->dispatch('swal', [
                    'action' => 'status',
                    'icon'   => 'error',
                    'title'  => 'Délai dépassé',
                    'text'   => "{$this->timeout} secondes écoulées, paiement non confirmé.",
                    'stopTimer' => true,
                ]);
                return;
            }
        } catch (\Throwable $e) {
            \Log::error('Erreur checkStatus: '.$e->getMessage());

            $this->status  = 'ERROR';
            $this->polling = false;

            $this->dispatch('swal', [
                'action' => 'status',
                'icon'   => 'error',
                'title'  => 'Erreur technique',
                'text'   => 'Une erreur est survenue lors de la vérification.',
                'stopTimer' => true,
            ]);
        }
    }
}
