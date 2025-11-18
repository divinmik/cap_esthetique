<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReminder extends Model
{
    //
    protected $fillable = ['user_id','invoice_id','type','channel','to_contact','message','sent_at'];
    protected $casts = ['sent_at'=>'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(Inscription::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
}
