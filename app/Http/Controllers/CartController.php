<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        /** @var User $user */
        $user  = Auth::user();
        $cart  = session()->get('cart', []);
        $games = [];
        $total = 0;

        foreach ($cart as $gameId => $item) {
            $game = Game::find($gameId);
            if ($game) {
                $games[] = ['game' => $game, 'quantity' => $item['quantity']];
                $total  += $game->price * $item['quantity'];
            }
        }

        return view('cart.index', compact('games', 'total'));
    }

    public function add(Request $request, Game $game)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->ownsGame($game->id)) {
            return back()->with('error', 'Kamu sudah memiliki game ini di library!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$game->id])) {
            return back()->with('info', 'Game sudah ada di keranjang.');
        }

        $cart[$game->id] = ['quantity' => 1];
        session()->put('cart', $cart);

        return back()->with('success', '"' . $game->title . '" berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request, Game $game)
    {
        $cart = session()->get('cart', []);
        unset($cart[$game->id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Game dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}