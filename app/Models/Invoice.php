<?php

namespace App\Models;

use App\Models\User;
use App\Models\Momopaiement;
use App\Models\PaymentReminder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    //
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'nature',
        'number',
        'reference',
        'title',
        'amount',
        'amount_paid',
        'status',
        'issue_date',
        'due_date',
        'meta',
        'submitted_at',
        'submitted_by',
        
    ];
    protected $casts = ['due_date'=>'date','meta'=>'array'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function isUnpaid(): bool { return in_array($this->status,['unpaid','partial']); }
    
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            // Génère automatiquement un code unique du style ECO-0001, ECO-0002...
            if (empty($invoice->reference)) {
                $next = (int) (self::max('id') ?? 0) + 1;
                $invoice->reference = 'FAC-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                // option: garantir l’unicité robuste
                while (self::where('reference', $invoice->code)->exists()) {
                    $next++;
                    $invoice->reference = 'FAC-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                }
            }
        });
    }

    
    public function payments()
    {
        return $this->hasMany(Momopaiement::class);
    }

    public function reminders()
    {
        return $this->hasMany(PaymentReminder::class);
    }

    /* Helpers */
    public function getBalanceCentsAttribute(): int
    {
        return max(0, (int)$this->amount - (int)$this->amount_paid);
    }

    public function recomputeStatus(): void
    {
        if ($this->status === 'cancelled') return;

        $due  = (int) $this->amount;
        $paid = (int) $this->amount_paid;

        if ($paid <= 0) {
            $this->status = 'unpaid';
        } elseif ($paid < $due) {
            $this->status = 'partial';
        } else {
            $this->status = 'paid';
            // verrouille au total pour éviter les dépassements
            $this->amount_paid = $due;
        }
    }

    /* Scopes utiles (optionnel) */
    public function scopePeriod($q, int $year, int $month)
    {
        return $q->where('year',$year)->where('month',$month);
    }

    public function scopeForCandidate($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeDue($q)
    {
        return $q->where('status','!=','paid')->whereDate('due_date','<', now());
    }
    
    public function candidate() { return $this->belongsTo(Candidate::class); }

}
