<?php

namespace App\Models;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // Protège rien (on sait ce qu’on enregistre depuis le back-office)
    protected $guarded = [];

    // Casts utiles
    protected $casts = [
        'received_at' => 'datetime',
        'meta'        => 'array',
    ];

    // Si tu veux que updated_at de la facture bouge quand un paiement change
    protected $touches = ['invoice'];

    /* =======================
     | Relations
     |=======================*/
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* =======================
     | Scopes pratiques
     |=======================*/
    public function scopeForInvoice($q, int $invoiceId)
    {
        return $q->where('invoice_id', $invoiceId);
    }

    public function scopeForUser($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeBetween($q, $from, $to)
    {
        return $q->whereBetween('received_at', [$from, $to]);
    }

    public function scopeMethod($q, string $method)
    {
        return $q->where('method', $method);
    }

    public function scopeWithExternalRef($q, string $ref)
    {
        return $q->where('external_ref', $ref);
    }

    /* =======================
     | Accessors / Helpers
     |=======================*/
    public function getAmountFormattedAttribute(): string
    {
        // Affichage en XAF sans décimales (car centimes déjà gérés en int)
        return number_format(((int)$this->amount_cents) / 100, 0, ',', ' ');
    }

    /**
     * Vérifie un doublon probable de paiement (idempotency “souple”)
     * - même facture + même ref externe, ou
     * - même facture + même idempotency_key, ou
     * - (optionnel) même facture + même montant + même ref
     */
    public static function existsDuplicate(int $invoiceId, ?string $externalRef = null, ?string $idempotencyKey = null, ?int $amountCents = null): bool
    {
        return static::query()
            ->when($externalRef, fn($q) => $q->where('external_ref', $externalRef))
            ->when($idempotencyKey, fn($q) => $q->orWhere('idempotency_key', $idempotencyKey))
            ->when($amountCents && $externalRef, fn($q) => $q->orWhere(function ($qq) use ($amountCents, $externalRef, $invoiceId) {
                $qq->where('invoice_id', $invoiceId)
                   ->where('external_ref', $externalRef)
                   ->where('amount_cents', $amountCents);
            }))
            ->where('invoice_id', $invoiceId)
            ->exists();
    }
}
