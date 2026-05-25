@extends('layouts.app')
@section('title', 'Teman Saya')
@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-user-group" style="color:var(--accent);"></i> Teman Saya</h1>
    </div>
</div>

<div class="section">
    <div class="container">

        {{-- Navigasi Community --}}
        <div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;">
            <a href="{{ route('community.forum.index') }}"
                class="btn {{ request()->routeIs('community.forum.*') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fa-solid fa-comments"></i> Forum
            </a>
            <a href="{{ route('community.friends.index') }}"
                class="btn {{ request()->routeIs('community.friends.*') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fa-solid fa-user-group"></i> Teman
                @if($pending->count() > 0)
                <span style="background:#fff;color:var(--accent);border-radius:999px;padding:0.1rem 0.45rem;font-size:0.7rem;margin-left:0.2rem;font-weight:700;">
                    {{ $pending->count() }}
                </span>
                @endif
            </a>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h3 style="color:var(--text-secondary);font-size:0.95rem;">
                {{ $friends->count() }} teman
            </h3>
            <a href="{{ route('community.friends.search') }}" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Cari Teman
            </a>
        </div>

        {{-- Permintaan Masuk --}}
        @if($pending->count() > 0)
        <div class="card card-body mb-4" style="border-color:var(--accent);">
            <h3 class="mb-3">
                <i class="fa-solid fa-bell" style="color:var(--accent);"></i>
                Permintaan Pertemanan
                <span style="background:var(--accent);color:#fff;border-radius:999px;padding:0.1rem 0.5rem;font-size:0.8rem;margin-left:0.3rem;">
                    {{ $pending->count() }}
                </span>
            </h3>
            @foreach($pending as $req)
            <div style="display:flex;align-items:center;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--border);">
                <img src="{{ $req->sender->avatar_url }}"
                    style="width:48px;height:48px;border-radius:50%;flex-shrink:0;object-fit:cover;">
                <div style="flex:1;">
                    <div style="font-weight:600;color:var(--text-primary);">{{ $req->sender->name }}</div>
                    <div style="font-size:0.85rem;color:var(--text-muted);">{{ '@' . $req->sender->username }}</div>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <form action="{{ route('community.friends.accept', $req) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check"></i> Terima
                        </button>
                    </form>
                    <form action="{{ route('community.friends.reject', $req) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Daftar Teman --}}
        @if($friends->count() > 0)
        <div class="grid-3">
            @foreach($friends as $friendship)
            @php
            $friend = $friendship->sender_id === auth()->id()
            ? $friendship->receiver
            : $friendship->sender;
            @endphp
            <div class="card card-body text-center">
                <img src="{{ $friend->avatar_url }}"
                    style="width:80px;height:80px;border-radius:50%;margin:0 auto 0.75rem;display:block;">
                <div style="font-weight:600;color:var(--text-primary);margin-bottom:0.2rem;">{{ $friend->name }}</div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;">{{ '@' . $friend->username }}</div>
                <div style="display:flex;gap:0.5rem;justify-content:center;">
                    <a href="{{ route('wishlist.public', $friend) }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-heart"></i> Wishlist
                    </a>
                    <form action="{{ route('community.friends.unfriend', $friend) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="color:var(--text-muted);"
                            onclick="return confirm('Hapus {{ $friend->name }} dari teman?')">
                            <i class="fa-solid fa-user-minus"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center" style="padding:4rem 0;">
            <i class="fa-solid fa-user-group" style="font-size:3rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
            <h3 style="color:var(--text-secondary);margin-bottom:0.5rem;">Belum ada teman</h3>
            <p style="color:var(--text-muted);margin-bottom:1.5rem;">Cari dan tambahkan teman untuk berbagi wishlist!</p>
            <a href="{{ route('community.friends.search') }}" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Cari Teman
            </a>
        </div>
        @endif

    </div>
</div>
@endsection