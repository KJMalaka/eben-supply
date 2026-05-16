<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'fulfillment',
        'total_amount',
        'contact_name',
        'contact_phone',
        'contact_email',
        'delivery_address',
        'payment_reference',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending'   => ['label' => 'Pending',   'class' => 'bg-yellow-100 text-yellow-800'],
            'confirmed' => ['label' => 'Confirmed', 'class' => 'bg-blue-100 text-blue-800'],
            'ready'     => ['label' => 'Ready',     'class' => 'bg-green-100 text-green-800'],
            'collected' => ['label' => 'Collected', 'class' => 'bg-gray-100 text-gray-800'],
            default     => ['label' => ucfirst($this->status), 'class' => 'bg-gray-100 text-gray-800'],
        };
    }

    public function getDeliveryFeeAttribute(): float
    {
        return $this->fulfillment === 'delivery' ? 60.00 : 0.00;
    }
}

