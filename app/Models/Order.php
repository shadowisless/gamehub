<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'recipient_id',
        'total_price',
        'status',
        'type',
        'gift_message',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Generate order number
    public static function generateOrderNumber(): string
    {
        return 'GH-' . strtoupper(uniqid());
    }
}
