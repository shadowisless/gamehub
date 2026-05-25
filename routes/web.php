<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\FriendController;

Route::get('/', function () {
    $featuredGames = \App\Models\Game::with('category')->active()->latest()->take(8)->get();
    $categories    = \App\Models\Category::withCount('games')->get();
    return view('home', compact('featuredGames', 'categories'));
})->name('home');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::get('/search', function (\Illuminate\Http\Request $request) {
    $games = \App\Models\Game::active()
        ->where('title', 'like', '%' . $request->q . '%')
        ->orWhere('developer', 'like', '%' . $request->q . '%')
        ->paginate(12);

    return view('games.index', [
        'games'      => $games,
        'categories' => \App\Models\Category::all(),
    ]);
})->name('search');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Katalog (Storefront) - public
Route::get('/store', [GameController::class, 'index'])->name('games.index');
Route::get('/store/{slug}', [GameController::class, 'show'])->name('games.show');

// Wishlist publik - siapapun bisa lihat
Route::get('/wishlist/{user:username}', [WishlistController::class, 'showPublic'])
    ->name('wishlist.public');

Route::middleware('auth')->group(function () {
    // Keranjang (Shopping Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{game}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{game}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout & Order
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Library (koleksi game milik user)
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

    // Wishlist
    Route::get('/my-wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{game}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::patch('/wishlist/visibility/{game}', [WishlistController::class, 'toggleVisibility'])->name('wishlist.visibility');

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::post('/games/{game}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
});

Route::prefix('community')->name('community.')->group(function () {

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{forum}', [ForumController::class, 'show'])->name('forum.show');
    Route::delete('/forum/{forum}', [ForumController::class, 'destroy'])->name('forum.destroy');
    Route::post('/forum/{forum}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    Route::delete('/forum/reply/{reply}', [ForumController::class, 'destroyReply'])->name('forum.reply.destroy');

    // Friends
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/send/{user}', [FriendController::class, 'send'])->name('friends.send');
    Route::patch('/friends/accept/{friend}', [FriendController::class, 'accept'])->name('friends.accept');
    Route::patch('/friends/reject/{friend}', [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/unfriend/{user}', [FriendController::class, 'unfriend'])->name('friends.unfriend');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [DashboardController::class, 'users'])->name('users');
        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');

        // Kategori
        Route::get('/categories', [DashboardController::class, 'categories'])->name('categories');
        Route::post('/categories', [DashboardController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [DashboardController::class, 'destroyCategory'])->name('categories.destroy');

        // Games CRUD
        Route::resource('games', AdminGameController::class);
    });
