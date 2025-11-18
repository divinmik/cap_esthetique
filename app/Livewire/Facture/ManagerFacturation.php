<?php

namespace App\Livewire\Facture;


use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Momopaiement;
use Livewire\WithPagination;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Carbon;
use App\Models\PaymentReminder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use SweetAlert2\Laravel\Traits\WithSweetAlert;
use Illuminate\Contracts\Database\Query\Builder;

class ManagerFacturation extends Component
{
    use WithPagination;
    use WithSweetAlert;
    protected $paginationTheme = 'bootstrap';

    /* =========================
     |  Filtres & Recherche
     |=========================*/
    public string $search = '';
    public string $status = '';
    public ?string $from = null; // YYYY-MM-DD
    public ?string $to   = null; // YYYY-MM-DD
    protected $queryString = ['search','status'];

    /* =========================
     |  États UI / Modals
     |=========================*/
    public bool $showEditor    = false; // modal create/edit facture
    public bool $showPayments  = false; // modal paiements
    public bool $showReminders = false; // modal relances

    public ?int $selectedInvoiceId = null; // contexte paiement/relance
    public ?int $editingId         = null; // facture en édition

    /* =========================
     |  Form Facture (create/edit)
     |=========================*/
    public int $user_id = 0;
    public string $number = '';
    public string $reference = '';
    public string $title = '';
    public string $nature = 'scolarite';
    public int $amount;
    public string $currency = 'XAF';
    public ?string $issue_date = null;
    public ?string $due_date   = null;
    public int $year; public int $month; // période de facturation

    /* =========================
     |  Génération (mois courant OU spécifié)
     |=========================*/
    public string $gen_nature = 'scolarite';
    public string $gen_title  = 'Frais de scolarité';
    public int $gen_amount;
    public ?int $gen_year  = null; // si null => now()
    public ?int $gen_month = null; // 1..12 (si null => now())

    /* =========================
     |  Paiement rapide
     |=========================*/
    public int $pay_amount = 20000;
    public string $pay_method = 'cash';
    public ?string $pay_ref = null;

    /* =========================
     |  Relance rapide
     |=========================*/
    public string $reminder_channel = 'sms'; // sms|email|both
    public ?string $reminder_to = null;
    public ?string $reminder_message = null;

    /* ========================= */
    public function mount(): void
    {
        $now = Carbon::now();
        $this->year  = (int)$now->year;
        $this->gen_year  = (int)$now->year;
        $this->month = (int)$now->month;
        $this->gen_month = (int)$now->month;
        $this->issue_date = $now->toDateString();
        $this->due_date   = $now->copy()->endOfMonth()->toDateString();
        $this->amount   = env('MONTANT_SCOLARITE');
        $this->gen_amount   = env('MONTANT_SCOLARITE');
       
    }

    /* =========================
     |  Computed
     |=========================*/
    public function getInvoicesProperty()
    {
        $q = Invoice::query()->with(['user','payments' => fn($p)=>$p->latest('created_at')]);

        if ($this->search !== '') {
            $s = Str::lower($this->search);
            $q->where(function($qq) use ($s){
                $qq->whereRaw('LOWER(number) like ?', ["%$s%"])
                   ->orWhereRaw('LOWER(reference) like ?', ["%$s%"])
                   ->orWhereRaw('LOWER(title) like ?', ["%$s%"])
                   ->orWhereHas('user', fn($u)=>$u->whereRaw('LOWER(firstname) like ?', ["%$s%"]))
                   ->orWhereHas('user', fn($u)=>$u->whereRaw('LOWER(email) like ?', ["%$s%"])) ;
            });
        }
        if ($this->status !== '') $q->where('status', $this->status);
        if ($this->from) $q->where(function($qq){ $qq->whereDate('issue_date','>=',$this->from)->orWhereDate('due_date','>=',$this->from); });
        if ($this->to)   $q->where(function($qq){ $qq->whereDate('issue_date','<=',$this->to)->orWhereDate('due_date','<=',$this->to); });

        return $q->orderBy('due_date')->paginate(15);
    }

    public function getStatsProperty(): array
    {
        $base = Invoice::query();
        return [
            'count' => (int)$base->clone()->count(),
            'total' => (int)$base->clone()->sum('amount'),
            'received' => (int)$base->clone()->sum('amount_paid'),
            'overdueCount' => (int)$base->clone()->where('status','!=','paid')->whereDate('due_date','<',now())->count(),
        ];
    }
    
    protected function candidatesQuery(): Builder
    {
        return User::query()
            ->where(function (Builder $q) {
                // Spatie (users_has_roles)
                $q->whereHas('roles', function (Builder $r) {
                    $r->where('name', 'candidat');
                })
                // Fallback si pas de package de rôles
                ->orWhere('role', 'candidat')
                ->orWhere('statut', 'candidat');
            })
            ->select('id','firstname','lastname','email');
    }

    public function getCandidatesProperty()
    {
        return $this->candidatesQuery()
            ->orderBy('firstname')
            ->limit(500)    // ajuste si besoin
            ->get();
    }
    
    //echenace
    protected function midNextMonth(?string $fromDate = null): string
    {
        $base = $fromDate ? Carbon::parse($fromDate) : now();
        return $base->copy()
            ->addMonthNoOverflow()
            ->startOfMonth()
            ->addDays(14) // 1er + 14j = 15
            ->toDateString();
    }


    /* =========================
     |  Génération
     |=========================*/
    public function generateForCurrentOrSpecified(): void
    {
        $y = $this->gen_year  ?: now()->year;
        $m = $this->gen_month ?: now()->month;
        $nature = trim($this->gen_nature);
        $title  = trim($this->gen_title);
        $amount = (int)$this->gen_amount;

        if ($amount <= 0) { $this->toast('error','Montant requis pour la génération.'); return; }
        if ($m < 1 || $m > 12) { $this->toast('error','Mois invalide.'); return; }

        $candidats = User::query()->where('statut','candidat')->get(['id','firstname','email']);
        $created = 0; $skipped = 0;

        DB::transaction(function() use ($candidats,$y,$m,$nature,$title,$amount,&$created,&$skipped){
            foreach ($candidats as $u) {
                $exists = Invoice::where(['user_id'=>$u->id,'nature'=>$nature,'year'=>$y,'month'=>$m])->exists();
                if ($exists) { $skipped++; continue; }
                $num = $this->makeNumber();
                Invoice::create([
                    'user_id'=>$u->id,
                    'year'=>$y,
                    'month'=>$m,
                    'nature'=>$nature,
                    'number'=>$num,
                    'title'=>$title,
                    'amount'=>$amount,
                    'amount_paid'=>0,
                    'currency'=>'XAF',
                    'status'=>'unpaid',
                    'issue_date'=>Carbon::create($y,$m,1)->toDateString(),
                    'due_date'=>$this->midNextMonth(Carbon::create($y,$m,1)->toDateString()),
                    //'due_date'=>Carbon::create($y,$m,1)->endOfMonth()->toDateString(),
                    'meta'=>['generated'=>'auto']
                ]);
                $created++;
            }
        });

        $this->toast('success', "Génération: $created créées, $skipped ignorées.");
    }

    protected function makeNumber(): string
    {
        $seq = (int) ((Invoice::max('id') ?? 0) + 1);
        return 'INV-'.str_pad((string)$seq, 5, '0', STR_PAD_LEFT);
    }

    /* =========================
     |  CRUD Facture
     |=========================*/
    public function openCreate(?int $userId=null): void
    {
        $this->resetEditor();
        if ($userId) $this->user_id = $userId;
        $this->showEditor = true;
    }

    public function openEdit(int $id): void
    {
        $this->resetEditor();
        $inv = Invoice::findOrFail($id);
        $this->editingId = $inv->id;
        $this->user_id = $inv->user_id;
        $this->number = $inv->number;
        $this->reference = (string)($inv->reference ?? '');
        $this->title = (string)($inv->title ?? '');
        $this->nature = $inv->nature;
        $this->amount = (int)$inv->amount;
        $this->currency = $inv->currency;
        $this->issue_date = optional($inv->issue_date)->toDateString();
        $this->due_date = optional($inv->due_date)->toDateString();
        $this->year = (int)$inv->year; $this->month = (int)$inv->month;
        $this->showEditor = true;
    }

    /* public function saveInvoice(): void
    {
        $this->validate($this->rules());

        if ($this->editingId) {
            $inv = Invoice::findOrFail($this->editingId);
            $inv->fill([
                'user_id'=>$this->user_id,
                'number'=>$this->number ?: $inv->number,
                'reference'=>$this->reference ?: $inv->reference,
                'title'=>$this->title,
                'nature'=>$this->nature,
                'amount'=>$this->amount,
                'currency'=>$this->currency,
                'issue_date'=>$this->issue_date,
                'due_date'=>$this->due_date,
                'year'=>$this->year,'month'=>$this->month,
            ]);
            $inv->recomputeStatus();
            $inv->save();
            $this->toast('success','Facture mise à jour.');
        } else {
            $num = $this->number ?: $this->makeNumber();
            Invoice::create([
                'user_id'=>$this->user_id,
                'number'=>$num,
                'title'=>$this->title,
                'nature'=>$this->nature,
                'amount'=>$this->amount,
                'amount_paid'=>0,
                'currency'=>$this->currency,
                'issue_date'=>$this->issue_date,
                'due_date'=>$this->due_date,
                'year'=>$this->year,'month'=>$this->month,
                'status'=>'unpaid',
            ]);
            $this->toast('success','Facture créée.');
        }

        $this->showEditor = false;
        $this->resetEditor();
    } */

    public function saveInvoice()
    {
        $this->validate($this->rules());
        $exists = Invoice::where(['user_id'=>$this->user_id,
        'nature'=>$this->nature,'year'=>$this->year,'month'=>$this->month])->exists();
                
        if($exists){
            $this->swalToastInfo([
                'title'  => 'Facture déjà existant'               
            ]);
        }else{
            if ($this->editingId) {
                $inv = Invoice::findOrFail($this->editingId);
                $inv->fill([
                    'user_id'=>$this->user_id,
                    'number'=>$this->number ?: $inv->number,
                    'title'=>$this->title,
                    'nature'=>$this->nature,
                    'amount'=>$this->amount,
                    'currency'=>$this->currency,
                    'issue_date'=>$this->issue_date,
                    'due_date'=>$this->due_date,
                    'year'=>$this->year,
                    'month'=>$this->month,
                ]);
                $inv->recomputeStatus();
                $inv->save();
                $this->swalToastSuccess([
                'title'  => 'Facture mise à jour'               
                ]);
            } else {
                $num = $this->number ?: $this->makeNumber();
                Invoice::create([
                    'user_id'=>$this->user_id,
                    'number'=>$num,
                    'title'=>$this->title,
                    'nature'=>$this->nature,
                    'amount'=>$this->amount,
                    'amount_paid'=>0,
                    'currency'=>$this->currency,
                    'issue_date'=>$this->issue_date,
                    'due_date'=>$this->due_date,
                    'year'=>$this->year,
                    'month'=>$this->month,
                    'status'=>'unpaid',
                ]);
                $this->swalToastSuccess([
                'title'  => 'Facture créée'               
                ]);
               
            }
        }
        
        $this->showEditor = false;
        $this->resetEditor();
    }

    public function delete(int $id): void
    {
        $inv = Invoice::where('id',$id)->where('status','unpaid')->first();
        if(!$inv){
            $this->toast('warning','impossible de faire cette action');
        }
        else{
            $inv->delete();
            $this->toast('success','Facture supprimée.');
        }
    }

    public function cancel(int $id): void
    {
        $inv = Invoice::where('id',$id)->where('status','unpaid')->first();
        if(!$inv){
            $this->toast('warning','impossible de faire cette action');
        }
        else{
            $inv->status = 'cancelled';
            $inv->save();
            $this->toast('warning','Facture annulée.');
        }
    }

    public function submit(int $id): void
    {
        DB::transaction(function () use ($id) {
            /** @var Invoice $inv */
            $inv = Invoice::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($inv->status === 'cancelled') {
                $this->toast('error', 'Impossible: facture annulée.');
                return;
            }

            $inv->issue_date ??= now()->toDateString();
            $inv->due_date   ??= now()->endOfMonth()->toDateString();

            if ($inv->submitted_at) {
                $this->toast('info', 'Déjà soumise le ' . $inv->submitted_at->format('d/m/Y H:i'));
                return;
            }

            $inv->submitted_at = now();
            $inv->submitted_by = auth()->id();
            $inv->save();
        });

        $this->toast('success','Facture soumise au candidat.');
    }

    /* =========================
     |  Paiements / Avances
     |=========================*/
    public function addPayment(int $id): void
    {
        $amount = (int) $this->pay_amount;
        if ($amount <= 0) { $this->toast('error','Montant invalide.'); return; }

        $method = trim($this->pay_method ?: 'cash');
        $extRef = $this->pay_ref ? trim($this->pay_ref) : null;

        try {
            DB::transaction(function () use ($id, $amount, $method, $extRef) {
                /** @var Invoice $inv */
                $inv = Invoice::whereKey($id)->lockForUpdate()->firstOrFail();

                if ($inv->status === 'cancelled') { $this->toast('error','Impossible: facture annulée.'); return; }
                if ($inv->status === 'paid')      { $this->toast('info','Déjà soldée.'); return; }

                $idem = $extRef ? hash('sha256', $inv->id.'|'.$extRef.'|'.$amount) : null;
                if ($idem && Payment::where('invoice_id',$inv->id)
                    ->where(function($q) use ($idem,$extRef){
                        $q->where('idempotency_key',$idem)->orWhere('external_ref',$extRef);
                    })->exists()) {
                    $this->toast('warning','Paiement déjà enregistré (idempotency).');
                    return;
                }

                $remaining = max(0, $inv->amount - $inv->amount_paid);
                if ($remaining <= 0) { $this->toast('info','Déjà soldée.'); return; }
                $toApply = min($amount, $remaining);

                Payment::create([
                    'invoice_id' => $inv->id,
                    'user_id'    => $inv->user_id,
                    'amount' => $toApply,
                    'method'     => $method,
                    'external_ref' => $extRef,
                    'received_at'  => now(),
                    'idempotency_key' => $idem,
                ]);

                $inv->amount_paid = (int) $inv->amount_paid + $toApply;
                $inv->recomputeStatus();
                $inv->save();

                if ($amount > $toApply) {
                    $this->toast('info', 'Le montant saisi excède le solde: seuls '.number_format($toApply/100,0,',',' ').' XAF appliqués.');
                }

                $this->selectedInvoiceId = $inv->id;
            });
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error','Erreur lors de l’enregistrement du paiement.');
            return;
        }

        $this->toast('success','Avance enregistrée.');
        $this->openPayments($id);
    }

    public function openPayments(int $id): void
    {
        $this->selectedInvoiceId = $id;
        $this->showPayments = true;
    }

    public function removePayment(int $paymentId): void
    {
        try {
            DB::transaction(function () use ($paymentId) {
                $p = Payment::with('invoice')->lockForUpdate()->findOrFail($paymentId);
                $inv = $p->invoice;

                $inv->amount_paid = max(0, (int)$inv->amount_paid - (int)$p->amount);
                $p->delete();
                $inv->recomputeStatus();
                $inv->save();

                $this->selectedInvoiceId = $inv->id;
            });
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Erreur lors de la suppression du paiement.');
            return;
        }

        $this->toast('success', 'Paiement supprimé.');
        $this->openPayments((int)$this->selectedInvoiceId);
    }

    public function markPaid(int $id): void
    {
        try {
            DB::transaction(function () use ($id) {
                $inv = Invoice::whereKey($id)->lockForUpdate()->firstOrFail();
                if ($inv->status === 'cancelled') { $this->toast('error','Impossible: facture annulée.'); return; }
                $inv->amount_paid = (int)$inv->amount;
                $inv->recomputeStatus();
                $inv->save();
            });
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Erreur lors du passage en payé.');
            return;
        }
        $this->toast('success', 'Facture soldée.');
    }

    public function recomputeFromPayments(int $invoiceId): void
    {
        try {
            DB::transaction(function () use ($invoiceId) {
                $inv = Invoice::whereKey($invoiceId)->lockForUpdate()->firstOrFail();
                $sum = (int) Payment::where('invoice_id', $inv->id)->sum('amount');
                $inv->amount_paid = $sum;
                $inv->recomputeStatus();
                $inv->save();
            });
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Erreur lors de la resynchronisation des paiements.');
            return;
        }
        $this->toast('success', 'Montants resynchronisés.');
    }

    /* =========================
     |  Relances
     |=========================*/
    public function sendReminder(int $id): void
    {
        $channels = ['sms','email','both'];
        $channel = in_array($this->reminder_channel, $channels, true) ? $this->reminder_channel : 'sms';

        try {
            $inv = Invoice::with('user')->findOrFail($id);

            $to = trim((string)($this->reminder_to ?? ''));
            if ($to === '') {
                $to = $channel === 'email' ? (string)$inv->user->email : (string) ($inv->user->phone ?? '');
            }
            if ($to === '') { $this->toast('error','Aucun destinataire valide.'); return; }

            $last = PaymentReminder::where('invoice_id',$inv->id)
                    ->where('channel',$channel)
                    ->latest('sent_at')->first();
            if ($last && $last->sent_at && $last->sent_at->gt(now()->subHour())) {
                $this->toast('warning','Relance déjà envoyée récemment.');
                $this->openReminders($inv->id);
                return;
            }

            $msg = $this->reminder_message ?: (
                'Bonjour '.$inv->user->name.', merci de régler la facture '.$inv->number.
                ' avant le '.optional($inv->due_date)->format('d/m/Y').'.'
            );

            PaymentReminder::create([
                'user_id'    => $inv->user_id,
                'invoice_id' => $inv->id,
                'type'       => 'invoice',
                'channel'    => $channel,
                'to_contact' => $to,
                'message'    => $msg,
                'sent_at'    => now(),
                'meta'       => [ 'ui' => 'manual', 'actor_id' => auth()->id() ],
            ]);

            // TODO: Brancher l'envoi réel (Wirepick/email)
            $this->toast('info','Relance enregistrée (envoi simulé).');
          
            $this->openReminders($inv->id);

        } catch (\Throwable $e) {
            report($e);
            $this->toast('error','Erreur lors de l\'envoi de la relance.');
        }
    }

    public function openReminders(int $id): void
    {
        $this->selectedInvoiceId = $id;
        $this->showReminders = true;
    }

    /* =========================
     |  Validation & Helpers
     |=========================*/
    protected function rules(): array
    {
        return [
            'user_id' => ['required','integer','exists:users,id'],
           /*  'number'  => ['nullable','string', Rule::unique('invoices','number')->ignore($this->editingId)],
            'reference' => ['nullable','string'], */
            'title'   => ['nullable','string','max:255'],
            'nature'  => ['required','string','max:50'],
            'amount' => ['required','integer','min:1'],
            'currency'=> ['required','string','size:3'],
            'issue_date' => ['required','date'],
            'due_date'   => ['required','date','after_or_equal:issue_date'],
            'year'    => ['required','integer','min:2000','max:2100'],
            'month'   => ['required','integer','min:1','max:12'],
        ];
    }

    public function resetEditor(): void
    {
        $now = now();
        $this->editingId = null;
        $this->user_id = 0;
        $this->number = '';
        $this->reference = '';
        $this->title = 'Frais de scolarite';
        $this->nature = 'scolarite';
        $this->amount = env('MONTANT_SCOLARITE');
        $this->currency = 'XAF';
        $this->issue_date = $now->toDateString();
        $this->due_date   = $now->copy()->endOfMonth()->toDateString();
        $this->year  = (int)$now->year; $this->month = (int)$now->month;
    }

    protected function toast(string $icon, string $text): void
    {
        $this->swalFire([
            'text' => $text,
            'icon' => $icon,
        ]);
    }

    public function render()
    {
        $invoices = $this->invoices; // pagination
        $stats    = $this->stats;
        $payments = collect();
        $reminders = collect();
        if ($this->selectedInvoiceId) {
            $payments  = Payment::where('invoice_id',$this->selectedInvoiceId)->latest('received_at')->get();
            $reminders = PaymentReminder::where('invoice_id',$this->selectedInvoiceId)->latest('sent_at')->get();
        }
        return view('livewire.facture.manager-facturation', compact('invoices','stats','payments','reminders'));
    }
}
