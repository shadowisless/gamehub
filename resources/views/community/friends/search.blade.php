@extends('layouts.app')
@section('title', 'Cari Teman')
@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-user-plus" style="color:var(--accent);"></i> Cari Teman</h1>
    </div>
</div>

<div class="section">
    <div class="container" style="max-width:650px;">

        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('community.friends.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- Form Cari --}}
        <form action="{{ route('community.friends.search') }}" method="GET" class="filter-bar mb-4">
            <input type="text" name="q" class="form-control"
                placeholder="Cari nama atau username..."
                value="{{ $query ?? '' }}" style="flex:1;" autofocus>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
        </form>

        {{-- Hasil Pencarian --}}
        @if(isset($users) && $users->count() > 0)
        <h4 style="color:var(--text-secondary);margin-bottom:0.75rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.5px;">
            {{ $users->count() }} hasil ditemukan
        </h4>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach($users as $u)
            @php
            /** @var \App\Models\User $authUser */
            $authUser = auth()->user();
            $isFriend = $authUser->isFriendWith($u->id);
            $isPending = $authUser->hasPendingRequestWith($u->id);
            @endphp
            <div class="card card-body" style="display:flex;align-items:center;gap:1rem;">
                <img src="{{ $u->avatar_url }}"
                    style="width:48px;height:48px;border-radius:50%;flex-shrink:0;">
                <div style="flex:1;">
                    <div style="font-weight:600;color:var(--text-primary);">{{ $u->name }}</div>
                    <div style="font-size:0.85rem;color:var(--text-muted);">{{ '@' . $u->username }}</div>
                </div>
                @if($isFriend)
                <span class="badge-pill badge-active">
                    <i class="fa-solid fa-check"></i> Sudah Teman
                </span>
                @elseif($isPending)
                <span class="badge-pill badge-gift">
                    <i class="fa-solid fa-clock"></i> Permintaan Terkirim
                </span>
                @else
                <form action="{{ route('community.friends.send', $u) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-user-plus"></i> Tambah Teman
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        @elseif(isset($query) && $query)
        {{-- Tidak ditemukan --}}
        <div class="text-center" style="padding:3rem 0;">
            <i class="fa-solid fa-user-slash" style="font-size:2.5rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <h3 style="color:var(--text-secondary);">User "{{ $query }}" tidak ditemukan</h3>
            <p style="color:var(--text-muted);margin-top:0.5rem;">Coba cari dengan nama atau username yang berbeda.</p>
        </div>

        @else
        {{-- Belum ada pencarian --}}
        <div class="text-center" style="padding:3rem 0;">
            <i class="fa-solid fa-magnifying-glass" style="font-size:2.5rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <p style="color:var(--text-muted);">Ketik nama atau username untuk mencari teman.</p>
        </div>
        @endif

    </div>
</div>
@endsection