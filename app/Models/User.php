<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function receivedGifts()
    {
        return $this->hasMany(Order::class, 'recipient_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistGames()
    {
        return $this->belongsToMany(Game::class, 'wishlists')->withTimestamps();
    }

    public function library()
    {
        return $this->hasMany(Library::class);
    }

    public function libraryGames()
    {
        return $this->belongsToMany(Game::class, 'libraries')
            ->withPivot('is_gift', 'order_id')
            ->withTimestamps();
    }

    // Helper
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function ownsGame(int $gameId): bool
    {
        return $this->libraryGames()->where('game_id', $gameId)->exists();
    }

    public function hasInWishlist(int $gameId): bool
    {
        return $this->wishlistGames()->where('game_id', $gameId)->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && $this->avatar !== 'default-avatar.png') {
            return asset('storage/avatars/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a1a2e&color=e94560&size=100&bold=true';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasReviewed(int $gameId): bool
    {
        return $this->reviews()->where('game_id', $gameId)->exists();
    }

    // Forum
    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

    // Friends
    public function sentRequests()
    {
        return $this->hasMany(Friend::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(Friend::class, 'receiver_id');
    }

    public function friends()
    {
        return Friend::where(function ($q) {
            $q->where('sender_id', $this->id)
                ->orWhere('receiver_id', $this->id);
        })->where('status', 'accepted')->get();
    }

    public function isFriendWith(int $userId): bool
    {
        return Friend::where(function ($q) use ($userId) {
            $q->where('sender_id', $this->id)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    public function hasPendingRequestWith(int $userId): bool
    {
        return Friend::where(function ($q) use ($userId) {
            $q->where('sender_id', $this->id)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $this->id);
        })->where('status', 'pending')->exists();
    }
}
