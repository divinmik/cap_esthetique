<?php
namespace App\Livewire\Candidat;

use App\Models\User;
use App\Models\Invoice;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Helper_function;
use App\Models\PaymentReminder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use SweetAlert2\Laravel\Traits\WithSweetAlert;
use App\Notifications\PaymentReminderNotification;

class Manager extends Component
{
    use WithPagination, WithFileUploads, WithSweetAlert;

    public string $search = '';
    public string $statusFilter = ''; // '', 'active', 'pending', 'blocked'
    protected $paginationTheme = 'bootstrap';

    public array $stats = [
        'count' => 0, 'active' => 0, 'blocked' => 0, 'with_photo' => 0,
    ];

    public string $action = 'create';
    public ?int $currentId = null;

    public $profile_photo_path; // TemporaryUploadedFile
    public ?string $profile_photo_url = null;

    public ?string $firstname = null;
    public ?string $lastname  = null;
    public ?string $email     = null;
    public ?string $phone     = null;
    public ?string $city      = null;
    public ?string $birthdate = null;
    public ?string $birthplace= null;
    public ?string $program   = null;
    public ?string $level     = null;
    public ?string $address     = null;
    public ?string $level_other = null;
    public ?string $is_actif = '1';

    protected function rules(): array
    {
        return [
            'firstname' => ['required','string','max:100'],
            'lastname'  => ['required','string','max:100'],
            'email'     => ['nullable','email','max:150'],
            'phone'     => [
                'required','phone:CG',
                function($attribute,$value,$fail){
                    $tel = Helper_function::phone($value);
                    $exists = User::where('phone',$tel)
                        ->when($this->currentId, fn($q)=>$q->where('id','!=',$this->currentId))
                        ->exists();
                    if($exists){ return $fail(__('Change le numéro de téléphone')); }
                    $this->phone = $tel;
                },
                Rule::unique('users','phone')->ignore($this->currentId),
            ],
            'city'      => ['nullable','string','max:100'],
            'birthdate' => ['nullable','date'],
            'birthplace'=> ['nullable','string','max:150'],
            'program'   => ['nullable','string','max:100'],
            'level'     => ['nullable', Rule::in(['CEP','BEPC','BAC','Autre'])],
            'level_other' => ['nullable','string','max:100'],
            'profile_photo_path' => ['nullable','image','max:5120'],
        ];
    }

    public function updatedSearch(){ $this->resetPage(); }
    public function updatedStatusFilter(){ $this->resetPage(); }

    public function render()
    {
        $base = User::query()
            ->where('role','candidat')
            ->when($this->search, function(Builder $q){
                $s = trim($this->search);
                $q->where(function(Builder $qq) use ($s){
                    $qq->where('firstname','like',"%{$s}%")
                       ->orWhere('lastname','like',"%{$s}%")
                       ->orWhere('email','like',"%{$s}%")
                       ->orWhere('phone','like',"%{$s}%")
                       ->orWhere('program','like',"%{$s}%");
                });
            })
            ->when($this->statusFilter !== '', fn($q)=>$q->where('is_actif',$this->statusFilter))
            ->latest('id');

        // Stats (pas stocker de paginator en propriété)
        $this->stats['count']      = (clone $base)->count();
        $this->stats['active']     = (clone $base)->where('is_actif','1')->count();
        $this->stats['blocked']    = (clone $base)->where('is_actif','0')->count();
        $this->stats['with_photo'] = (clone $base)->whereNotNull('profile_photo_path')->where('profile_photo_path','!=','')->count();

        // Paginator local → mappé puis retourné à la vue
        $page = (clone $base)->paginate(12);

        $ids = collect($page->items())->pluck('id')->all();
        $unpaid = Invoice::query()
            ->select('id','user_id','number','amount','status','due_date','title')
            ->whereIn('user_id',$ids)
            ->whereIn('status',['unpaid','partial'])
            ->orderBy('due_date')
            ->get()
            ->groupBy('user_id');

        $page->setCollection(
            $page->getCollection()->map(function(User $u) use ($unpaid){
                $profileUrl = $u->profile_photo_path
                    ? (Str::startsWith($u->profile_photo_path,['http://','https://'])
                        ? $u->profile_photo_path
                        : route('docs.display', ['filename' => str_replace('profils/', '', $u->profile_photo_path)]))
                    : null;

                return [
                    'id'         => $u->id,
                    'firstname'  => $u->firstname,
                    'lastname'   => $u->lastname,
                    'email'      => $u->email,
                    'phone'      => $u->phone,
                    'city'       => $u->city,
                    'birthdate'  => optional($u->birthdate)->format('Y-m-d') ?? $u->birthdate,
                    'birthplace' => $u->birthplace,
                    'program'    => $u->program,
                    'level'      => $u->level,
                    'level_other'=> $u->level_other,
                    'address'=> $u->address,
                    'status'     => $u->status,
                    'profile_photo_url' => $profileUrl,
                    'unpaid_invoices'   => ($unpaid[$u->id] ?? collect())->map(function($inv){
                        return [
                            'id'       => $inv->id,
                            'number'   => $inv->number,
                            'amount'   => (int)$inv->amount,
                            'status'   => $inv->status,
                            'due_date' => optional($inv->due_date)->format('Y-m-d'),
                            'title'    => $inv->title,
                        ];
                    })->values()->all(),
                    'code'       => $u->code ?? null,
                    'is_actif'   => $u->is_actif,
                ];
            })
        );

        return view('livewire.candidat.manager', [
            'candidates' => $page, // <- on passe le paginator à la vue uniquement
        ]);
    }

    public function startEdit(int $id): void
    {
        $u = User::where('role','candidat')->findOrFail($id);
        $this->action = 'edit';
        $this->currentId = $u->id;

        $this->firstname = $u->firstname;
        $this->lastname  = $u->lastname;
        $this->email     = $u->email;
        $this->phone     = $u->phone;
        $this->city      = $u->city;
        $this->birthdate = optional($u->birthdate)->format('Y-m-d') ?? $u->birthdate;
        $this->birthplace= $u->birthplace;
        $this->program   = $u->program;
        $this->level     = $u->level;
        $this->level_other = $u->level_other;
        $this->address = $u->address;

        $this->profile_photo_url = $u->profile_photo_path
            ? (Str::startsWith($u->profile_photo_path,['http://','https://'])
                ? $u->profile_photo_path
                : route('docs.display', ['filename' => str_replace('profils/', '', $u->profile_photo_path)]))
            : null;

        $this->is_actif = $u->status === 'blocked' ? '0' : '1';
    }

    public function toggleActive(int $id): void
    {
        $u = User::where('role','candidat')->findOrFail($id);
        if ($u->is_actif === '1') {
            $u->update(['is_actif'=>'1','blocked_at'=>null]);
            $this->swal('success','Débloqué','Le compte a été réactivé.');
        } else {
            $u->update(['is_actif'=>'0','blocked_at'=>now()]);
            $this->swal('warning','Bloqué','Le compte a été bloqué.');
        }
    }

    public function delete(int $id): void
    {
        $u = User::where('role','candidat')->findOrFail($id);
        $u->delete();
        $this->swal('success','Supprimé','Candidat supprimé.');
        $this->resetForm();
    }

    public function save(): void
    {
        $this->action = 'create';
        $data = $this->validate();

        if ($this->profile_photo_path) {
            $data['profile_photo_path'] = $this->profile_photo_path->store('profiles','public');
        }
        
        $data['role']   = 'candidat';
        //$data['status'] = 'pending';

        $user = User::create($data);

       /*  if (!Invoice::where('user_id',$user->id)->where('title',"Frais d'inscription")->exists()) {
            Invoice::create([
                'user_id'=>$user->id,
                'number'=>'INV-'.Str::upper(Str::random(8)),
                'title'=>"Frais d'inscription",
                'amount'=>10000,
                'status'=>'unpaid',
                'due_date'=>now()->addDays(7),
            ]);
        } */

        $this->swal('success','Créé','Candidat ajouté.');
        $this->resetForm();
    }

    public function edit(): void
    {
        if(!$this->currentId) return;
        $this->action = 'edit';
        $data = $this->validate();

        if ($this->profile_photo_path) {
            $data['profile_photo_path'] = $this->profile_photo_path;
            $name = 'profilcand_'.Str::random(12).'_'.$this->currentId.'.'.$data['profile_photo_path']->getClientOriginalExtension();
            $signaturePath = $data['profile_photo_path']->storeAs('profils', $name, 'public');
            $data['profile_photo_path'] = $name;
        }
        else{
         unset($data['profile_photo_path']);   
        }

        $data['is_actif'] = $this->is_actif == '1' ? '1' : '0';
        //$data['blocked_at'] = $data['status'] === 'blocked' ? now() : null;
       
        $u = User::where('role','candidat')->findOrFail($this->currentId);
        $u->update($data);

        $this->swal('success','Mis à jour','Candidat modifié.');
        $this->resetForm();
    }

    public function markPaid(int $invoiceId): void
    {
        $inv = Invoice::findOrFail($invoiceId);
        $inv->update(['status'=>'paid']);
        if ($inv->title === "Frais d'inscription") {
            $inv->user?->update(['status'=>'active','blocked_at'=>null]);
        }
        $this->swal('success','Payée','Facture marquée payée.');
    }

    public function sendReminder(int $userId, string $kind='registration', ?int $invoiceId=null, string $channel='both'): void
    {
        $u = User::where('role','candidat')->findOrFail($userId);
        $invoice = $invoiceId ? Invoice::findOrFail($invoiceId) : null;

        $amountTxt = $invoice ? number_format((int)$invoice->amount,0,' ',' ') : 'les frais d’inscription';
        $payUrl = url('/payment-inscription?ref='.($invoice? $invoice->number : $u->id));

        $msg = match ($kind) {
            'registration' => "Bonjour {$u->firstname}, merci de finaliser {$amountTxt}. Lien: {$payUrl}",
            'invoice'      => "Bonjour {$u->firstname}, votre facture ".($invoice? $invoice->number:'')." est en attente. Montant: {$amountTxt}. Lien: {$payUrl}",
            default        => "Bonjour {$u->firstname}, rappel de paiement. Lien: {$payUrl}",
        };

        if (in_array($channel,['sms','both'])) {
           // app(\App\Services\Sms\WirepickClient::class)->sendAsync($u->phone, $msg);
        }
        if (in_array($channel,['email','both']) && $u->email) {
           // Notification::route('mail', $u->email)->notify(new PaymentReminderNotification($msg));
        }

        PaymentReminder::create([
            'user_id'   => $u->id,
            'invoice_id'=> $invoice?->id,
            'type'      => $kind,
            'channel'   => $channel,
            'to_contact'=> $channel==='email' ? ($u->email ?? '') : $u->phone,
            'message'   => $msg,
            'sent_at'   => now(),
        ]);

        $this->swal('success','Relance envoyée','Le candidat a été relancé.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'action','currentId','profile_photo_path','profile_photo_url',
            'firstname','lastname','email','phone','city','birthdate','birthplace',
            'program','level','level_other','is_actif'
        ]);
        $this->action = 'create';
        $this->is_actif = '1';
    }

    private function swal(string $icon, string $title, string $text=''): void
    {
            $this->swalFire([
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
        ]);

        //$this->dispatch('swal', icon:$icon, title:$title, text:$text);
    }
}
