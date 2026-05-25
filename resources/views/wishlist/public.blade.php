@extends('layouts.app')

@section('title', 'Wishlist ' . $user->name)

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>
            <i class="fa-solid fa-heart" style="color: var(--accent);"></i>
            Wishlist {{ $user->name }}
        </h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">{{ '@' . $user->username }}</p>
    </div>
</div>

<div class="section">
    <div class="container">
        @if($wishlists->count() > 0)
        <div class="games-grid">
            @foreach($wishlists as $wl)
            <div class="card game-card">
                <a href="{{ route('games.show', $wl->game->slug) }}">
                    <img src="{{ $wl->game->cover_url }}" alt="{{ $wl->game->title }}" class="game-card-img"
                         onerror="this.src='https://via.placeholder.com/320x180/1a1a2e/e94560?text={{ urlencode($wl->game->title) }}'">
                </a>
                <div class="game-card-body">
                    <div class="game-card-category">{{ $wl->game->category->name ?? '-' }}</div>
                    <div class="game-card-title">{{ $wl->game->title }}</div>
                    <div class="game-card-footer">
                        <span class="game-price">{{ $wl->game->formatted_price }}</span>
                    </div>
                    @auth
                    <div class="mt-2">
                        {{-- Hadiahkan game ini ke user --}}
                        <form action="{{ route('cart.add', $wl->game) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                                <i class="fa-solid fa-gift"></i> Hadiahkan
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center" style="padding: 5rem 0;">
            <h3 style="color: var(--text-secondary);">Wishlist {{ $user->name }} masih kosong atau privat.</h3>
        </div>
        @endif
    </div>
</div>
@endsection