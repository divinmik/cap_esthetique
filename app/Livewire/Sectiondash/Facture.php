<?php

namespace App\Livewire\Sectiondash;

use Livewire\Component;
use Illuminate\Support\Str;

class Facture extends Component
{
    // Filtres et état UI
    public string $search = '';
    public ?string $status = null; // unpaid|partial|paid|null
    public ?string $from = null;
    public ?string $to = null;

    /** @var array<int, array> */
    public array $invoices = [];

    public function mount(): void
    {
        // --- FAKE DATA (pas de base de données) ---
        $cands = [
            ['id'=>1,'name'=>'Mbelolo Kossi','email'=>'mbelolo@example.com'],
            ['id'=>2,'name'=>'Nadia Mbemba','email'=>'nadia@example.com'],
            ['id'=>3,'name'=>'Ruben Diallo','email'=>'ruben@example.com'],
            ['id'=>4,'name'=>'Sarah Sita','email'=>'sarah@example.com'],
            ['id'=>5,'name'=>'Achille Tchicaya','email'=>'achille@example.com'],
            ['id'=>6,'name'=>'Yasmina Ndinga','email'=>'yasmina@example.com'],
        ];

        $make = function($i,$cand,$amount,$paid,$issue,$due,$channel='mtn'){
            $status = $paid >= $amount ? 'paid' : ($paid>0 ? 'partial' : 'unpaid');
            return [
                'id'           => $i,
                'number'       => 'FAC-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'candidate'    => $cand,
                'currency'     => 'XAF',
                'amount'       => $amount,       // entiers (pas de décimales)
                'amount_paid'  => $paid,
                'issue_date'   => $issue,        // 'Y-m-d'
                'due_date'     => $due,          // 'Y-m-d'
                'status'       => $status,       // unpaid|partial|paid
                'reference'    => strtoupper(Str::random(8)),
                'channel'      => $channel,
            ];
        };

        $this->invoices = [
            $make(1,$cands[0],150000,150000,now()->subDays(10)->toDateString(), now()->subDays(3)->toDateString(),'bank'),
            $make(2,$cands[1],120000,  20000,now()->subDays(7)->toDateString(),  now()->addDays(3)->toDateString(),'mtn'),
            $make(3,$cands[2], 80000,      0,now()->subDays(5)->toDateString(),  now()->subDays(1)->toDateString(),'airtel'),
            $make(4,$cands[3],200000, 50000,now()->subDays(3)->toDateString(),   now()->addDays(10)->toDateString(),'cash'),
            $make(5,$cands[4], 90000, 90000,now()->subDays(1)->toDateString(),   now()->addDays(5)->toDateString(),'mtn'),
            $make(6,$cands[5],110000,      0,now()->toDateString(),              now()->addDays(7)->toDateString(),'bank'),
            $make(7,$cands[0], 60000, 30000,now()->subDays(14)->toDateString(),  now()->subDays(2)->toDateString(),'airtel'),
            $make(8,$cands[1],135000,135000,now()->subDays(20)->toDateString(),  now()->subDays(10)->toDateString(),'cash'),
            $make(9,$cands[2], 70000, 10000,now()->subDays(9)->toDateString(),   now()->addDays(2)->toDateString(),'mtn'),
            $make(10,$cands[3],50000,      0,now()->subDays(12)->toDateString(), now()->subDays(3)->toDateString(),'bank'),
        ];
    }

    // --- Helpers ---
    private function recomputeStatus(array &$inv): void
    {
        if ($inv['amount_paid'] >= $inv['amount']) {
            $inv['status'] = 'paid';
        } elseif ($inv['amount_paid'] > 0) {
            $inv['status'] = 'partial';
        } else {
            $inv['status'] = 'unpaid';
        }
    }

    private function filterInvoices(): array
    {
        $list = $this->invoices;

        // Recherche
        if ($this->search !== '') {
            $term = Str::lower($this->search);
            $list = array_values(array_filter($list, function($i) use ($term){
                return Str::contains(Str::lower($i['number']), $term)
                    || Str::contains(Str::lower($i['candidate']['name']), $term)
                    || Str::contains(Str::lower($i['candidate']['email']), $term)
                    || Str::contains(Str::lower($i['reference']), $term);
            }));
        }

        // Statut
        if ($this->status) {
            $list = array_values(array_filter($list, fn($i)=> $i['status'] === $this->status));
        }

        // Période d’émission
        if ($this->from) {
            $list = array_values(array_filter($list, fn($i)=> $i['issue_date'] >= $this->from));
        }
        if ($this->to) {
            $list = array_values(array_filter($list, fn($i)=> $i['issue_date'] <= $this->to));
        }

        // Tri récent -> ancien
        usort($list, fn($a,$b)=> strcmp($b['issue_date'], $a['issue_date']));
        return $list;
    }

    public function getStatsProperty(): array
    {
        $all = $this->filterInvoices();
        $sum = fn($cb)=> array_sum(array_map($cb, $all));

        $total     = $sum(fn($i)=> $i['amount']);
        $received  = $sum(fn($i)=> $i['amount_paid']);
        $paid      = $sum(fn($i)=> $i['status']==='paid' ? $i['amount'] : 0);
        $unpaid    = $sum(fn($i)=> $i['status']==='unpaid' ? $i['amount'] : 0);
        $partial   = $sum(fn($i)=> $i['status']==='partial' ? ($i['amount'] - $i['amount_paid']) : 0);
        $overdues  = array_filter($all, function($i){
            return $i['status']!=='paid'
                && !empty($i['due_date'])
                && now()->gt($i['due_date']);
        });

        return [
            'count'        => count($all),
            'total'        => $total,
            'received'     => $received,
            'paid'         => $paid,
            'unpaid'       => $unpaid,
            'partial'      => $partial,
            'overdueCount' => count($overdues),
        ];
    }

    // --- Actions ---
    public function markPaid(int $id): void
    {
        foreach ($this->invoices as &$inv) {
            if ($inv['id'] === $id) {
                $inv['amount_paid'] = $inv['amount'];
                $this->recomputeStatus($inv);
                break;
            }
        }
        $this->dispatch('swal', title: 'Payée', text: 'Facture marquée comme payée', icon: 'success');
    }

    public function addPayment(int $id, int $value): void
    {
        foreach ($this->invoices as &$inv) {
            if ($inv['id'] === $id) {
                $inv['amount_paid'] = min($inv['amount'], $inv['amount_paid'] + $value);
                $this->recomputeStatus($inv);
                break;
            }
        }
        $this->dispatch('swal', title: 'Paiement ajouté', text: 'Montant partiel enregistré', icon: 'success');
    }

    public function delete(int $id): void
    {
        $this->invoices = array_values(array_filter($this->invoices, fn($i)=> $i['id'] !== $id));
        $this->dispatch('swal', title: 'Supprimée', text: 'Facture supprimée', icon: 'success');
    }

    public function sendReminder(int $id): void
    {
        $inv = collect($this->invoices)->firstWhere('id', $id);
        $name = $inv['candidate']['name'] ?? 'candidat';
        // Ici tu peux brancher ton envoi SMS/Email (Wirepick, Mailables, etc.)
        $this->dispatch('swal', title: 'Rappel envoyé', text: "Rappel envoyé à {$name}", icon: 'info');
    }

    public function render()
    {
        $invoices = $this->filterInvoices();
        $stats = $this->stats; // via accessor

        return view('livewire.sectiondash.facture', compact('invoices','stats'))
            ->title('Gestion des factures (Fake data)');
    }
}
