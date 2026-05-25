@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-heart" style="color: var(--accent);"></i> Wishlist Saya</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">
            Bagikan: <code style="background: var(--bg-secondary); padding: 0.2rem 0.5rem; border-radius: 4px;">{{ route('wishlist.public', auth()->user()) }}</code>
        </p>
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
                        <span class="badge-pill {{ $wl->is_public ? 'badge-active' : 'badge-inactive' }}" style="font-size: 0.65rem;">
                            {{ $wl->is_public ? 'Publik' : 'Privat' }}
                        </span>
                    </div>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                        <form action="{{ route('wishlist.visibility', $wl->game) }}" method="POST" style="flex: 1;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-outline btn-sm btn-block" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-{{ $wl->is_public ? 'eye-slash' : 'eye' }}"></i>
                                {{ $wl->is_public ? 'Privat' : 'Publik' }}
                            </button>
                        </form>
                        <form action="{{ route('wishlist.toggle', $wl->game) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm btn-block" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center" style="padding: 5rem 0;">
            <i class="fa-regular fa-heart" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
            <h3 style="color: var(--text-secondary); margin-bottom: 0.5rem;">Wishlist Masih Kosong</h3>
            <a href="{{ route('games.index') }}" class="btn btn-primary mt-3">Jelajahi Game</a>
        </div>
        @endif
    </div>
</div>
@endsection