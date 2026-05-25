<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $games = [];
        $total = 0;

        foreach ($cart as $gameId => $item) {
            $game = Game::find($gameId);
            if ($game) {
                $games[] = ['game' => $game, 'quantity' => $item['quantity']];
                $total  += $game->price * $item['quantity'];
            }
        }

        // Untuk dropdown gifting (semua user kecuali diri sendiri)
        $users = User::where('id', '!=', Auth::id())->get();

        return view('orders.checkout', compact('games', 'total', 'users'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'type'         => ['required', 'in:purchase,gift'],
            'recipient_id' => ['required_if:type,gift', 'nullable', 'exists:users,id'],
            'gift_message' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        /** @var User $user */
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $total = 0;
            $items = [];

            foreach ($cart as $gameId => $item) {
                $game    = Game::findOrFail($gameId);
                $total  += $game->price * $item['quantity'];
                $items[] = [
                    'game'     => $game,
                    'quantity' => $item['quantity'],
                    'price'    => $game->price,
                ];
            }

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id'      => $user->id,
                'recipient_id' => $validated['type'] === 'gift' ? $validated['recipient_id'] : null,
                'total_price'  => $total,
                'status'       => 'completed',
                'type'         => $validated['type'],
                'gift_message' => $validated['gift_message'] ?? null,
            ]);

            // Penerima game: diri sendiri atau teman (jika gift)
            $recipientId = $validated['type'] === 'gift'
                ? $validated['recipient_id']
                : $user->id;

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'game_id'  => $item['game']->id,
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Library::firstOrCreate(
                    [
                        'user_id' => $recipientId,
                        'game_id' => $item['game']->id,
                    ],
                    [
                        'order_id' => $order->id,
                        'is_gift'  => $validated['type'] === 'gift',
                    ]
                );
            }

            session()->forget('cart');

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Transaksi berhasil! Game telah ditambahkan ke library.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        /** @var User $user */
        $user   = Auth::user();
        $orders = $user->orders()
                       ->with('items.game')
                       ->latest()
                       ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.game', 'recipient');

        return view('orders.show', compact('order'));
    }
}