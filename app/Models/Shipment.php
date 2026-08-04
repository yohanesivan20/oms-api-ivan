<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Enums\ShippingStatus;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier',
        'service',
        'shipping_cost',
        'tracking_number',
        'estimated_delivery',
        'status',
        'response',
    ];

    protected $casts = [
        'status' => ShippingStatus::class,
        
        'shipping_cost' => 'decimal:2',
        'response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(ShipmentTracking::class);
    }
}
