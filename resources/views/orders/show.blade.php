@extends('layouts.app')

@section('title', 'Detail Order ' . $order->order_number)

@section('content')
<div class="section">
    <div class="container" style="max-width: 800px;">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 style="font-size: 1.5rem;">Detail Order</h1>
        </div>

        {{-- Order Info --}}
        <div class="card card-body mb-3">
            <div class="grid-2">
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">No. Order</div>
                    <div style="font-family: var(--font-display); font-size: 1.2rem; color: var(--text-primary);">{{ $order->order_number }}</div>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</div>
                    <div>{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</div>
                    <span class="status-{{ $order->status }} font-bold">● {{ ucfirst($order->status) }}</span>
                </div>
                <div>
                    <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tipe</div>
                    <span class="badge-pill {{ $order->type === 'gift' ? 'badge-gift' : 'badge-purchase' }}">
                        {{ $order->type === 'gift' ? '🎁 Kado' : '👤 Pembelian Sendiri' }}
                    </span>
                </div>
            </div>

            @if($order->type === 'gift' && $order->recipient)
            <div style="margin-top: 1rem; padding: 1rem; background: rgba(233,69,96,0.08); border: 1px solid var(--border-accent); border-radius: var(--radius);">
                <div style="font-size: 0.8rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">Dikirim ke</div>
                <div style="font-weight: 600;">{{ $order->recipient->name }}</div>
                @if($order->gift_message)
                <div style="margin-top: 0.5rem; color: var(--text-secondary); font-style: italic;">"{{ $order->gift_message }}"</div>
                @endif
            </div>
            @endif
        </div>

        {{-- Items --}}
        <div class="card card-body mb-3">
            <h3 class="mb-3">Item yang Dibeli</h3>
            @foreach($order->items as $item)
            <div class="cart-item">
                <img src="{{ $item->game->cover_url }}" alt="{{ $item->game->title }}" class="cart-item-img"
                    onerror="this.src='https://via.placeholder.com/80x50/1a1a2e/e94560?text=Game'">
                <div class="cart-item-info">
                    <div class="cart-item-title">{{ $item->game->title }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $item->game->developer }}</div>
                </div>
                <div class="cart-item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
            @endforeach

            <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 0.5rem;">
                <div class="flex justify-between font-bold" style="font-size: 1.1rem;">
                    <span>Total</span>
                    <span style="color: var(--accent); font-family: var(--font-display); font-size: 1.3rem;">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('games.index') }}" class="btn btn-outline" style="margin-left:0.5rem;">
            <i class="fa-solid fa-store"></i> Belanja Lagi
        </a>
        <a href="{{ route('library.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-book-open"></i> Lihat Library
        </a>
    </div>
</div>
@endsection