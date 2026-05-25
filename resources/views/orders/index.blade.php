@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-receipt" style="color: var(--accent);"></i> Riwayat Transaksi</h1>
    </div>
</div>

<div class="section">
    <div class="container">
        @if($orders->count() > 0)
        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Tanggal</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td style="font-family: var(--font-display); color: var(--text-primary);">{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $order->items->count() }} game</td>
                        <td style="color: var(--accent); font-weight: 600;">{{ $order->formatted_total }}</td>
                        <td>
                            <span class="badge-pill {{ $order->type === 'gift' ? 'badge-gift' : 'badge-purchase' }}">
                                <i class="fa-solid fa-{{ $order->type === 'gift' ? 'gift' : 'user' }}"></i>
                                {{ $order->type === 'gift' ? 'Kado' : 'Beli' }}
                            </span>
                        </td>
                        <td>
                            <span class="status-{{ $order->status }}">
                                ● {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
        @else
        <div class="text-center" style="padding: 5rem 0;">
            <i class="fa-solid fa-receipt" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-secondary);">Belum ada transaksi</h3>
            <a href="{{ route('games.index') }}" class="btn btn-primary mt-3">Mulai Belanja</a>
        </div>
        @endif
    </div>
</div>
@endsection