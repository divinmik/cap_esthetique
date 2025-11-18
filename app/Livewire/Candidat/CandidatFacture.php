<?php

namespace App\Livewire\Candidat;

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
use Illuminate\Support\Facades\Auth;
use SweetAlert2\Laravel\Traits\WithSweetAlert;
use Illuminate\Contracts\Database\Query\Builder;

class CandidatFacture extends Component
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
     |  Paiement rapide
     |=========================*/
    public int $pay_amount = 20000;
    public string $pay_method = 'cash';
    public ?string $pay_ref = null;



    /* ========================= */
    public function mount(): void
    {

       
    }

    /* =========================
     |  Computed
     |=========================*/
    public function getInvoicesProperty()
    {
        $q = Invoice::query()
        ->with(['user','payments' => fn($p) => $p->latest('created_at')])
        ->where('user_id', auth()->id());

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

    public function markPaid(int $id): void
    {
        try {
           /*  DB::transaction(function () use ($id) {
                $inv = Invoice::whereKey($id)->lockForUpdate()->firstOrFail();
                if ($inv->status === 'cancelled') { $this->toast('error','Impossible: facture annulée.'); return; }
                $inv->amount_paid = (int)$inv->amount;
                $inv->recomputeStatus();
                $inv->save();
            }); */
        } catch (\Throwable $e) {
            report($e);
            $this->toast('error', 'Erreur lors du passage en payé.');
            return;
        }
        $this->toast('success', 'Facture soldée.');
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
        $payments = collect();
        $payments = collect();
        $reminders = collect();
        if ($this->selectedInvoiceId) {
            $payments  = Payment::where('invoice_id',$this->selectedInvoiceId)->latest('received_at')->get();
            $reminders = PaymentReminder::where('invoice_id',$this->selectedInvoiceId)->latest('sent_at')->get();
        }
        return view('livewire.candidat.candidat-facture', compact('invoices','payments'));
    }
}
