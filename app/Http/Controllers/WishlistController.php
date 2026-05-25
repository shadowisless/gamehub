<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        /** @var User $user */
        $user      = Auth::user();
        $wishlists = $user->wishlists()
                          ->with('game.category')
                          ->latest()
                          ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Game $game)
    {
        /** @var User $user */
        $user     = Auth::user();
        $existing = Wishlist::where('user_id', $user->id)
                            ->where('game_id', $game->id)
                            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', '"' . $game->title . '" dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id'   => $user->id,
            'game_id'   => $game->id,
            'is_public' => true,
        ]);

        return back()->with('success', '"' . $game->title . '" ditambahkan ke wishlist!');
    }

    public function toggleVisibility(Game $game)
    {
        /** @var User $user */
        $user     = Auth::user();
        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('game_id', $game->id)
                            ->firstOrFail();

        $wishlist->update(['is_public' => !$wishlist->is_public]);

        $status = $wishlist->is_public ? 'publik' : 'privat';

        return back()->with('success', 'Wishlist diubah ke mode ' . $status . '.');
    }

    // Wishlist publik milik user lain — tidak butuh auth
    public function showPublic(User $user)
    {
        $wishlists = $user->wishlists()
                          ->where('is_public', true)
                          ->with('game.category')
                          ->latest()
                          ->get();

        return view('wishlist.public', compact('user', 'wishlists'));
    }
}