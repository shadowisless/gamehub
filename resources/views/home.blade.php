@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <span class="hero-label"><i class="fa-solid fa-fire"></i> Platform Gaming #1</span>
            <h1>Temukan Game<br><span>Favoritmu</span></h1>
            <p>Platform distribusi game digital global. Beli game, hadiahkan ke teman, dan kelola koleksimu dalam satu tempat.</p>
            <div class="hero-actions">
                <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-store"></i> Jelajahi Store
                </a>
                @guest
                <a href="{{ route('register') }}" class="btn btn-outline btn-lg">Daftar Gratis</a>
                @endguest
            </div>
        </div>
    </div>
</section>

{{-- Kategori --}}
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Jelajahi <span>Genre</span></h2>
            <a href="{{ route('games.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            @foreach($categories as $cat)
            <a href="{{ route('games.index', ['category' => $cat->id]) }}" class="btn btn-secondary">
                <i class="fa-solid fa-tag"></i> {{ $cat->name }}
                <span style="color: var(--text-muted); font-size: 0.8rem;">({{ $cat->games_count }})</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Games --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Game <span>Terbaru</span></h2>
            <a href="{{ route('games.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
        </div>
        <div class="games-grid">
            @forelse($featuredGames as $game)
            <a href="{{ route('games.show', $game->slug) }}" class="card game-card">
                <img src="{{ $game->cover_url }}" alt="{{ $game->title }}" class="game-card-img"
                     onerror="this.src='https://via.placeholder.com/320x180/1a1a2e/e94560?text={{ urlencode($game->title) }}'">
                <div class="game-card-body">
                    <div class="game-card-category">{{ $game->category->name ?? '-' }}</div>
                    <div class="game-card-title">{{ $game->title }}</div>
                    <div class="game-card-developer">{{ $game->developer }}</div>
                    <div class="game-card-footer">
                        <span class="game-price {{ $game->price == 0 ? 'game-free' : '' }}">
                            {{ $game->price == 0 ? 'GRATIS' : $game->formatted_price }}
                        </span>
                        <span class="btn btn-primary btn-sm">Beli</span>
                    </div>
                </div>
            </a>
            @empty
            <p class="text-muted">Belum ada game tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Fitur --}}
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header" style="justify-content: center; text-align: center; display: block; margin-bottom: 2.5rem;">
            <h2 class="section-title">Kenapa <span>GameHub</span>?</h2>
        </div>
        <div class="grid-3">
            <div class="card card-body text-center">
                <i class="fa-solid fa-gift" style="font-size:2rem; color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Tukar Kado Digital</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Hadiahkan game ke teman dengan pesan personal. Langsung masuk ke library mereka!</p>
            </div>
            <div class="card card-body text-center">
                <i class="fa-solid fa-heart" style="font-size:2rem; color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Wishlist Terintegrasi</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Simpan game impian dan bagikan wishlist-mu agar teman tahu game apa yang kamu inginkan.</p>
            </div>
            <div class="card card-body text-center">
                <i class="fa-solid fa-book-open" style="font-size:2rem; color: var(--accent); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Library Pribadi</h3>
                <p class="text-muted" style="font-size: 0.9rem;">Semua game yang kamu beli dan terima tersimpan rapi di library pribadimu selamanya.</p>
            </div>
        </div>
    </div>
</section>

@endsection