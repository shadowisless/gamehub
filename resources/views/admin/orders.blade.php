@extends('layouts.app')
@section('title', 'Admin — Orders')
@section('content')
<div class="admin-layout">
  @include('admin.partials.sidebar')
  <div class="admin-content">
    <h1 style="font-size:1.75rem;margin-bottom:1.5rem;">Semua Transaksi</h1>
    <div class="card overflow-x-auto">
      <form action="{{ route('admin.orders') }}" method="GET" class="filter-bar mb-3">
        <input type="text" name="search" class="form-control"
          placeholder="Cari nama pembeli atau no. order..."
          value="{{ request('search') }}" style="flex:1;">
        <select name="type" class="form-control">
          <option value="">Semua Tipe</option>
          <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Pembelian</option>
          <option value="gift" {{ request('type') == 'gift'     ? 'selected' : '' }}>Kado</option>
        </select>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-magnifying-glass"></i> Filter
        </button>
        @if(request()->hasAny(['search', 'type']))
        <a href="{{ route('admin.orders') }}" class="btn btn-outline">Reset</a>
        @endif
      </form>
      <table class="table">
        <thead>
          <tr>
            <th>No. Order</th>
            <th>Pembeli</th>
            <th>Penerima</th>
            <th>Total</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
          <tr>
            <td style="font-size:0.8rem;color:var(--text-primary);">{{ $order->order_number }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->recipient?->name ?? '—' }}</td>
            <td style="color:var(--accent);font-weight:600;">{{ $order->formatted_total }}</td>
            <td>
              <span class="badge-pill {{ $order->type === 'gift' ? 'badge-gift' : 'badge-purchase' }}">
                {{ $order->type === 'gift' ? 'Kado' : 'Beli' }}
              </span>
            </td>
            <td><span class="status-{{ $order->status }}">● {{ ucfirst($order->status) }}</span></td>
            <td>{{ $order->created_at->format('d M Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
  </div>
</div>
@endsection