<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'developer',
        'publisher',
        'price',
        'cover_image',
        'trailer_url',
        'screenshots',
        'system_requirements',
        'release_date',
        'status',
        'category_id',
        'stock',
    ];

    protected $casts = [
        'screenshots'          => 'array',
        'system_requirements'  => 'array',
        'release_date'         => 'date',
        'price'                => 'decimal:2',
    ];

    // ── Relasi ───────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    public function usersInLibrary()
    {
        return $this->belongsToMany(User::class, 'libraries')->withTimestamps();
    }

    // ✅ Tambahkan ini
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ── Accessors ─────────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/covers/' . $this->cover_image)
            : asset('images/default-cover.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // ── Scopes ────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}