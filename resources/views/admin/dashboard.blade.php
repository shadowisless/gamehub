@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Content --}}
    <div class="admin-content">
        <div class="flex justify-between items-center mb-4">
            <h1 style="font-size: 1.75rem;">Dashboard</h1>
            <div style="color: var(--text-secondary); font-size: 0.9rem;">{{ now()->format('d F Y') }}</div>
        </div>

        {{-- Stats --}}
        <div class="grid-4 mb-4" style="grid-template-columns: repeat(4, 1fr);">
            <div class="card stat-card">
                <div class="stat-icon"><i class="fa-solid fa-gamepad"></i></div>
                <div class="stat-value">{{ $stats['total_games'] }}</div>
                <div class="stat-label">Total Game</div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total User</div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Order Selesai</div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="stat-value" style="font-size: 1.4rem;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>

        <div class="grid-2">
            {{-- Recent Orders --}}
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Order Terbaru</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Order</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td style="font-size: 0.8rem; color: var(--text-primary);">{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td style="color: var(--accent);">{{ $order->formatted_total }}</td>
                                <td>
                                    <span class="badge-pill {{ $order->type === 'gift' ? 'badge-gift' : 'badge-purchase' }}">
                                        {{ $order->type === 'gift' ? 'Kado' : 'Beli' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Popular Games --}}
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-3">Game Terlaris</h3>
                    @foreach($popularGames as $i => $game)
                    <div class="flex items-center gap-2" style="padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
                        <div style="font-family: var(--font-display); font-size: 1.2rem; color: var(--text-muted); width: 24px; text-align: center;">
                            {{ $i + 1 }}
                        </div>
                        <div style="flex: 1;">
                            <div style="color: var(--text-primary); font-weight: 600;">{{ $game->title }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $game->sold_count }} terjual</div>
                        </div>
                        <span style="color: var(--accent); font-family: var(--font-display);">{{ $game->formatted_price }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection