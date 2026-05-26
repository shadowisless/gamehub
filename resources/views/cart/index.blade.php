@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')

<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-cart-shopping" style="color:var(--accent);"></i> Keranjang Belanja</h1>
    </div>
</div>

<div class="section">
    <div class="container">
        @if(count($games) > 0)
        {{-- Layout: 2 kolom di desktop, 1 kolom di mobile --}}
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; align-items:start;" class="game-detail-grid">

            {{-- Daftar Item --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-3">
                        <h3>{{ count($games) }} Game</h3>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-sm"
                                    onclick="return confirm('Kosongkan keranjang?')">
                                <i class="fa-solid fa-trash"></i> Kosongkan
                            </button>
                        </form>
                    </div>

                    @foreach($games as $item)
                    <div class="cart-item">
                        <img src="{{ $item['game']->cover_url }}" alt="{{ $item['game']->title }}"
                             class="cart-item-img"
                             onerror="this.src='https://via.placeholder.com/80x50/1a1a2e/e94560?text=Game'">
                        <div class="cart-item-info">
                            <div class="cart-item-title">{{ $item['game']->title }}</div>
                            <div style="font-size:0.8rem; color:var(--text-secondary);">{{ $item['game']->developer }}</div>
                        </div>
                        <div class="cart-item-price">{{ $item['game']->formatted_price }}</div>
                        <form action="{{ route('cart.remove', $item['game']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color:var(--text-muted);" title="Hapus">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Summary --}}
            <div class="card card-body game-action-card" style="position:sticky; top:90px;">
                <h3 class="mb-3">Ringkasan</h3>
                <div class="flex justify-between mb-3" style="font-size:0.95rem; color:var(--text-secondary);">
                    <span>{{ count($games) }} item</span>
                    <span style="color:var(--text-primary);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div style="border-top:1px solid var(--border); padding-top:1rem; margin-bottom:1.25rem;">
                    <div class="flex justify-between font-bold" style="font-size:1.1rem;">
                        <span>Total</span>
                        <span style="color:var(--accent); font-family:var(--font-display); font-size:1.3rem;">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-block btn-lg">
                    <i class="fa-solid fa-credit-card"></i> Lanjut Checkout
                </a>
                <a href="{{ route('games.index') }}" class="btn btn-outline btn-block mt-2">
                    <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
                </a>
            </div>
        </div>

        @else
        <div class="text-center" style="padding:4rem 0;">
            <i class="fa-solid fa-cart-shopping" style="font-size:4rem; color:var(--text-muted); margin-bottom:1.5rem; display:block;"></i>
            <h2 style="color:var(--text-secondary); margin-bottom:0.5rem;">Keranjangmu Kosong</h2>
            <p style="color:var(--text-muted); margin-bottom:2rem;">Yuk tambahkan game favoritmu!</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-store"></i> Jelajahi Store
            </a>
        </div>
        @endif
    </div>
</div>
@endsection