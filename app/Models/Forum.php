<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'title',
        'body',
        'category',
        'views',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class); 
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'tips'       => 'Tips & Trick',
            'review'     => 'Review',
            'bug_report' => 'Bug Report',
            default      => 'General',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'tips'       => 'badge-active',
            'review'     => 'badge-purchase',
            'bug_report' => 'badge-inactive',
            default      => 'badge-gift',
        };
    }
}