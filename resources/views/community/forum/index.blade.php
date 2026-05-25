@extends('layouts.app')
@section('title', 'Forum Komunitas')
@section('content')
<div class="page-hero">
    <div class="container">
        <h1><i class="fa-solid fa-comments" style="color:var(--accent);"></i> Forum Komunitas</h1>
        <p style="color:var(--text-secondary);margin-top:0.5rem;">Diskusi, tips, dan review bersama gamer lainnya</p>
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
            @auth
            <a href="{{ route('community.friends.index') }}"
                class="btn {{ request()->routeIs('community.friends.*') ? 'btn-primary' : 'btn-secondary' }}">
                <i class="fa-solid fa-user-group"></i> Teman
                @php
                /** @var \App\Models\User $authUser */
                $authUser = auth()->user();
                $pendingCount = \App\Models\Friend::where('receiver_id', $authUser->id)
                ->where('status', 'pending')
                ->count();
                @endphp
                @if($pendingCount > 0)
                <span style="background:var(--accent);color:#fff;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.7rem;margin-left:0.2rem;">
                    {{ $pendingCount }}
                </span>
                @endif
            </a>
            @endauth
        </div>

        {{-- Filter + Tombol Buat --}}
        <div class="flex justify-between items-center mb-3">
            <form action="{{ route('community.forum.index') }}" method="GET" class="filter-bar" style="flex:1;margin-right:1rem;">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari diskusi..." value="{{ request('search') }}" style="flex:1;">
                <select name="category" class="form-control">
                    <option value="">Semua Kategori</option>
                    <option value="general" {{ request('category') == 'general'    ? 'selected' : '' }}>General</option>
                    <option value="tips" {{ request('category') == 'tips'       ? 'selected' : '' }}>Tips & Trick</option>
                    <option value="review" {{ request('category') == 'review'     ? 'selected' : '' }}>Review</option>
                    <option value="bug_report" {{ request('category') == 'bug_report' ? 'selected' : '' }}>Bug Report</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('community.forum.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                @endif
            </form>
            @auth
            <a href="{{ route('community.forum.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Buat Diskusi
            </a>
            @endauth
        </div>

        {{-- Daftar Forum --}}
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @forelse($forums as $forum)
            <a href="{{ route('community.forum.show', $forum) }}" class="card" style="display:block;">
                <div class="card-body" style="display:flex;gap:1rem;align-items:center;">
                    <img src="{{ $forum->user->avatar_url }}"
                        style="width:48px;height:48px;border-radius:50%;flex-shrink:0;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.25rem;">
                            <span class="badge-pill {{ $forum->category_color }}" style="font-size:0.65rem;">
                                {{ $forum->category_label }}
                            </span>
                            @if($forum->game)
                            <span style="font-size:0.75rem;color:var(--text-muted);">
                                <i class="fa-solid fa-gamepad"></i> {{ $forum->game->title }}
                            </span>
                            @endif
                        </div>
                        <div style="font-weight:600;color:var(--text-primary);font-family:var(--font-display);font-size:1.05rem;">
                            {{ $forum->title }}
                        </div>
                        <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.2rem;">
                            oleh <span style="color:var(--text-secondary);">{{ $forum->user->name }}</span>
                            · {{ $forum->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:0.85rem;color:var(--text-secondary);">
                            <i class="fa-solid fa-reply"></i> {{ $forum->replies->count() }}
                        </div>
                        <div style="font-size:0.8rem;color:var(--text-muted);">
                            <i class="fa-solid fa-eye"></i> {{ $forum->views }}
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center" style="padding:4rem 0;">
                <i class="fa-solid fa-comments" style="font-size:3rem;color:var(--text-muted);margin-bottom:1rem;display:block;"></i>
                <h3 style="color:var(--text-secondary);">Belum ada diskusi</h3>
                @auth
                <a href="{{ route('community.forum.create') }}" class="btn btn-primary mt-3">Mulai Diskusi</a>
                @endauth
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($forums->hasPages())
        <div style="display:flex;justify-content:center;gap:0.4rem;margin-top:2rem;flex-wrap:wrap;">
            @if($forums->onFirstPage())
            <span style="padding:0.4rem 0.9rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);color:var(--text-muted);opacity:0.4;">‹</span>
            @else
            <a href="{{ $forums->previousPageUrl() }}" style="padding:0.4rem 0.9rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);color:var(--text-secondary);text-decoration:none;">‹</a>
            @endif

            @foreach($forums->getUrlRange(1, $forums->lastPage()) as $page => $url)
            @if($page == $forums->currentPage())
            <span style="padding:0.4rem 0.9rem;background:var(--accent);border:1px solid var(--accent);border-radius:var(--radius);color:#fff;font-weight:600;">{{ $page }}</span>
            @else
            <a href="{{ $url }}" style="padding:0.4rem 0.9rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);color:var(--text-secondary);text-decoration:none;">{{ $page }}</a>
            @endif
            @endforeach

            @if($forums->hasMorePages())
            <a href="{{ $forums->nextPageUrl() }}" style="padding:0.4rem 0.9rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);color:var(--text-secondary);text-decoration:none;">›</a>
            @else
            <span style="padding:0.4rem 0.9rem;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);color:var(--text-muted);opacity:0.4;">›</span>
            @endif
        </div>
        @endif

    </div>
</div>
@endsection