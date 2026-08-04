<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Enums\PaymentStatus;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_reference',
        'provider',
        'amount',
        'payment_url',
        'status',
        'paid_at',
        'expired_at',
        'response',
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
