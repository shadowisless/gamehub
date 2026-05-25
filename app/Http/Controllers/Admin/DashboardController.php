<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_games'    => Game::count(),
            'total_users'    => User::where('role', 'user')->count(),
            'total_orders'   => Order::where('status', 'completed')->count(),
            'total_revenue'  => Order::where('status', 'completed')->sum('total_price'),
            'total_gifts'    => Order::where('type', 'gift')->where('status', 'completed')->count(),
        ];

        $recentOrders = Order::with('user', 'items.game')
            ->latest()
            ->take(10)
            ->get();

        $popularGames = Game::withCount(['orderItems as sold_count'])
            ->orderBy('sold_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'popularGames'));
    }

    public function users()
    {
        $users = User::withCount(['orders', 'library as library_games_count'])
            ->latest()
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function orders()
    {
        $query = Order::with('user', 'items.game', 'recipient')->latest();

        if (request()->filled('search')) {
            $query->where('order_number', 'like', '%' . request('search') . '%')
                ->orWhereHas(
                    'user',
                    fn($q) =>
                    $q->where('name', 'like', '%' . request('search') . '%')
                );
        }

        if (request()->filled('type')) {
            $query->where('type', request('type'));
        }

        $orders = $query->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function categories()
    {
        $categories = Category::withCount('games')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:categories'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->games()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih ada game yang menggunakan kategori ini.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
