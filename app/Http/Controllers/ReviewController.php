<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function store(Request $request, Game $game)
    {
        /** @var User $user */
        $user = Auth::user();

        // Hanya user yang punya game di library yang bisa review
        if (!$user->ownsGame($game->id)) {
            return back()->with('error', 'Kamu harus memiliki game ini untuk memberikan review!');
        }

        // Cek sudah pernah review
        if ($user->hasReviewed($game->id)) {
            return back()->with('error', 'Kamu sudah pernah mereview game ini!');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title'  => ['nullable', 'string', 'max:100'],
            'body'   => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        Review::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'rating'  => $validated['rating'],
            'title'   => $validated['title'] ?? null,
            'body'    => $validated['body'],
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($review->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }
}