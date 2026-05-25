<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with('category')->active();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('developer', 'like', '%' . $request->search . '%')
                  ->orWhere('publisher', 'like', '%' . $request->search . '%');
            });
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'title'      => $query->orderBy('title', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $games      = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        // ✅ Tambahkan ini — kirim owned game ids ke view
        $ownedGameIds = [];
        if (Auth::check()) {
            /** @var User $user */
            $user         = Auth::user();
            $ownedGameIds = $user->libraryGames()->pluck('games.id')->toArray();
        }

        return view('games.index', compact('games', 'categories', 'ownedGameIds'));
    }

    public function show(string $slug)
    {
        $game = Game::with('category')
                    ->where('slug', $slug)
                    ->active()
                    ->firstOrFail();

        $relatedGames = Game::active()
            ->where('category_id', $game->category_id)
            ->where('id', '!=', $game->id)
            ->take(4)
            ->get();

        $userOwns       = false;
        $userInWishlist = false;

        if (Auth::check()) {
            /** @var User $user */
            $user           = Auth::user();
            $userOwns       = $user->ownsGame($game->id);
            $userInWishlist = $user->hasInWishlist($game->id);
        }

        return view('games.show', compact('game', 'relatedGames', 'userOwns', 'userInWishlist'));
    }
}