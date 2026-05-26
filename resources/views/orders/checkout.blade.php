@extends('layouts.app')
@section('title', 'Checkout')
@section('content')

<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-credit-card" style="color:var(--accent);"></i> Checkout</h1>
    </div>
</div>

<div class="section">
    <div class="container">
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:2rem; align-items:start;" class="game-detail-grid">

            {{-- Form Checkout --}}
            <div>
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    {{-- Tipe Pembelian --}}
                    <div class="card card-body mb-3">
                        <h3 class="mb-3"><i class="fa-solid fa-tag"></i> Tipe Pembelian</h3>

                        <div style="display:flex; gap:1rem; margin-bottom:1rem;" class="checkout-type-labels">
                            <label style="flex:1; display:flex; flex-direction:column; align-items:center; padding:1.25rem; border:2px solid var(--border); border-radius:var(--radius); cursor:pointer; transition:var(--transition); text-align:center;" class="purchase-label">
                                <input type="radio" name="type" value="purchase" checked style="display:none;" id="type-purchase">
                                <i class="fa-solid fa-user" style="font-size:1.5rem; margin-bottom:0.5rem;"></i>
                                <span style="font-weight:600;">Untuk Diri Sendiri</span>
                            </label>
                            <label style="flex:1; display:flex; flex-direction:column; align-items:center; padding:1.25rem; border:2px solid var(--border); border-radius:var(--radius); cursor:pointer; transition:var(--transition); text-align:center;" class="gift-label">
                                <input type="radio" name="type" value="gift" style="display:none;" id="type-gift">
                                <i class="fa-solid fa-gift" style="font-size:1.5rem; color:var(--accent); margin-bottom:0.5rem;"></i>
                                <span style="font-weight:600;">Hadiah ke Teman</span>
                            </label>
                        </div>

                        <div id="gift-fields" style="display:none;">
                            <div class="form-group">
                                <label class="form-label">Pilih Penerima</label>
                                <select name="recipient_id" class="form-control">
                                    <option value="">-- Pilih teman --</option>
                                    @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ '@' . $u->username }})</option>
                                    @endforeach
                                </select>
                                @error('recipient_id')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pesan Ucapan (Opsional)</label>
                                <textarea name="gift_message" class="form-control" rows="3"
                                          placeholder="Tulis pesan untuk temanmu...">{{ old('gift_message') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Item --}}
                    <div class="card card-body mb-3">
                        <h3 class="mb-3"><i class="fa-solid fa-list"></i> Item Pesanan</h3>
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
                        </div>
                        @endforeach
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="card card-body mb-3">
                        <h3 class="mb-3"><i class="fa-solid fa-wallet"></i> Metode Pembayaran</h3>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="radio" name="payment_method" value="wallet" checked>
                            <i class="fa-solid fa-wallet" style="color:var(--accent);"></i> GameHub Wallet
                        </label>
                        <div style="margin-top:1rem; padding:0.75rem 1rem; background:var(--bg-secondary); border-radius:var(--radius); font-size:0.85rem; color:var(--text-secondary);">
                            <i class="fa-solid fa-circle-info" style="color:#64b5f6;"></i>
                            Simulasi pembayaran untuk UAS. Transaksi langsung diproses.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg"
                            onclick="return confirm('Konfirmasi pembelian?')">
                        <i class="fa-solid fa-check"></i>
                        Konfirmasi — Rp {{ number_format($total, 0, ',', '.') }}
                    </button>
                </form>
            </div>

            {{-- Summary --}}
            <div class="card card-body game-action-card" style="position:sticky; top:90px;">
                <h3 class="mb-3">Total Pembayaran</h3>
                @foreach($games as $item)
                <div class="flex justify-between mb-2" style="font-size:0.85rem; color:var(--text-secondary);">
                    <span style="flex:1; padding-right:0.5rem;">{{ Str::limit($item['game']->title, 20) }}</span>
                    <span style="white-space:nowrap;">{{ $item['game']->formatted_price }}</span>
                </div>
                @endforeach
                <div style="border-top:1px solid var(--border); padding-top:1rem; margin-top:0.5rem;">
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span style="color:var(--accent); font-family:var(--font-display); font-size:1.3rem;">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
const purchaseRadio = document.getElementById('type-purchase');
const giftRadio     = document.getElementById('type-gift');
const giftFields    = document.getElementById('gift-fields');
const purchaseLabel = document.querySelector('.purchase-label');
const giftLabel     = document.querySelector('.gift-label');

function updateType() {
    if (giftRadio.checked) {
        giftFields.style.display = 'block';
        giftLabel.style.borderColor = 'var(--accent)';
        giftLabel.style.background = 'rgba(233,69,96,0.05)';
        purchaseLabel.style.borderColor = 'var(--border)';
        purchaseLabel.style.background = 'transparent';
    } else {
        giftFields.style.display = 'none';
        purchaseLabel.style.borderColor = 'var(--accent)';
        purchaseLabel.style.background = 'rgba(233,69,96,0.05)';
        giftLabel.style.borderColor = 'var(--border)';
        giftLabel.style.background = 'transparent';
    }
}
purchaseRadio.addEventListener('change', updateType);
giftRadio.addEventListener('change', updateType);
updateType();
</script>
@endpush
@endsection