@extends('layouts.app')

@section('title', 'Library')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-book-open" style="color: var(--accent);"></i> Library Saya</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">{{ $games->total() }} game dalam koleksimu</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <form action="{{ route('library.index') }}" method="GET" class="filter-bar mb-4">
            <input type="text" name="search" class="form-control" placeholder="Cari di library..."
                   value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </form>

        @if($games->count() > 0)
        <div class="games-grid">
            @foreach($games as $game)
            <a href="{{ route('games.show', $game->slug) }}" class="card game-card">
                <img src="{{ $game->cover_url }}" alt="{{ $game->title }}" class="game-card-img"
                     onerror="this.src='https://via.placeholder.com/320x180/1a1a2e/e94560?text={{ urlencode($game->title) }}'">
                <div class="game-card-body">
                    <div class="game-card-category">{{ $game->category->name ?? '-' }}</div>
                    <div class="game-card-title">{{ $game->title }}</div>
                    <div class="game-card-developer">{{ $game->developer }}</div>
                    <div class="game-card-footer">
                        <span class="badge-pill badge-active"><i class="fa-solid fa-check"></i> Dimiliki</span>
                        @if($game->pivot->is_gift ?? false)
                        <span class="badge-pill badge-gift"><i class="fa-solid fa-gift"></i> Kado</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        {{ $games->links() }}
        @else
        <div class="text-center" style="padding: 5rem 0;">
            <i class="fa-solid fa-book-open" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
            <h3 style="color: var(--text-secondary); margin-bottom: 0.5rem;">Library Masih Kosong</h3>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Beli game pertamamu sekarang!</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-store"></i> Ke Store
            </a>
        </div>
        @endif
    </div>
</div>
@endsection