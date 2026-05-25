@extends('layouts.app')

@section('title', 'Store')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-store" style="color: var(--accent);"></i> Game Store</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">{{ $games->total() }} game tersedia</p>
    </div>
</div>

<div class="section">
    <div class="container">

        {{-- Filter Bar --}}
        <form action="{{ route('games.index') }}" method="GET" class="filter-bar">
            <input type="text" name="search" class="form-control" placeholder="Cari game..."
                   value="{{ request('search') }}" style="flex: 1; min-width: 200px;">

            <select name="category" class="form-control">
                <option value="">Semua Genre</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>

            <select name="sort" class="form-control">
                <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="title"      {{ request('sort') == 'title'      ? 'selected' : '' }}>Nama A-Z</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'category', 'sort']))
            <a href="{{ route('games.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
            @endif
        </form>

        {{-- Game Grid --}}
        <div class="games-grid">
            @forelse($games as $game)
            <a href="{{ route('games.show', $game->slug) }}" class="card game-card">
                <img src="{{ $game->cover_url }}" alt="{{ $game->title }}" class="game-card-img"
                     onerror="this.src='https://via.placeholder.com/320x180/1a1a2e/e94560?text={{ urlencode($game->title) }}'">
                <div class="game-card-body">
                    <div class="game-card-category">{{ $game->category->name ?? '-' }}</div>
                    <div class="game-card-title">{{ $game->title }}</div>
                    <div class="game-card-developer">{{ $game->developer }}</div>
                    <div class="game-card-footer">
                        <span class="game-price">
                            {{ $game->price == 0 ? 'GRATIS' : $game->formatted_price }}
                        </span>
                        {{-- ✅ Badge "Dimiliki" di dalam loop --}}
                        @auth
                            @if(in_array($game->id, $ownedGameIds))
                            <span class="badge-pill badge-active" style="font-size:0.7rem;">
                                <i class="fa-solid fa-check"></i> Dimiliki
                            </span>
                            @endif
                        @endauth
                    </div>
                </div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 4rem 0;">
                <i class="fa-solid fa-ghost" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3 style="color: var(--text-secondary);">Tidak ada game ditemukan</h3>
                <a href="{{ route('games.index') }}" class="btn btn-outline mt-3">Reset Filter</a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 2rem;">
            {{ $games->withQueryString()->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@push('styles')
<style>
/* Override Bootstrap pagination agar sesuai dark theme GameHub */
.pagination {
    display: flex;
    gap: 0.4rem;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}
.pagination .page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.6rem;
    border-radius: var(--radius);
    background: var(--bg-card);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    font-size: 0.85rem;
    text-decoration: none;
    transition: var(--transition);
}
.pagination .page-item .page-link:hover {
    background: var(--bg-hover);
    border-color: var(--accent);
    color: var(--text-primary);
}
.pagination .page-item.active .page-link {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    font-weight: 600;
}
.pagination .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>
@endpush

@endsection