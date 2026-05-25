@extends('layouts.app')
@section('title', $forum->title)
@section('content')
<div class="section">
    <div class="container" style="max-width:800px;">
        <a href="{{ route('community.forum.index') }}" class="btn btn-outline btn-sm mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Forum
        </a>

        {{-- Post Utama --}}
        <div class="card card-body mb-3">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;">
                <span class="badge-pill {{ $forum->category_color }}">{{ $forum->category_label }}</span>
                @if($forum->game)
                <span style="font-size:0.8rem;color:var(--text-muted);">
                    <i class="fa-solid fa-gamepad"></i> {{ $forum->game->title }}
                </span>
                @endif
                <span style="font-size:0.8rem;color:var(--text-muted);margin-left:auto;">
                    <i class="fa-solid fa-eye"></i> {{ $forum->views }} views
                </span>
            </div>

            <h1 style="font-size:1.6rem;margin-bottom:1rem;">{{ $forum->title }}</h1>

            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1px solid var(--border);">
                <img src="{{ $forum->user->avatar_url }}"
                    style="width:40px;height:40px;border-radius:50%;">
                <div>
                    <div style="font-weight:600;color:var(--text-primary);">{{ $forum->user->name }}</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);">{{ $forum->created_at->diffForHumans() }}</div>
                </div>
                @auth
                @if(auth()->id() === $forum->user_id || auth()->user()->isAdmin())
                <form action="{{ route('community.forum.destroy', $forum) }}" method="POST" style="margin-left:auto;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus diskusi ini?')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
                @endif
                @endauth
            </div>

            <div style="color:var(--text-secondary);line-height:1.8;white-space:pre-line;">{{ $forum->body }}</div>
        </div>

        {{-- Replies --}}
        <h3 style="margin-bottom:1rem;">{{ $forum->replies->count() }} Balasan</h3>

        @foreach($forum->replies as $reply)
        <div class="card card-body mb-2">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                <img src="{{ $reply->user->avatar_url }}"
                    style="width:36px;height:36px;border-radius:50%;">
                <div style="flex:1;">
                    <div style="font-weight:600;color:var(--text-primary);">{{ $reply->user->name }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $reply->created_at->diffForHumans() }}</div>
                </div>
                @auth
                @if(auth()->id() === $reply->user_id || auth()->user()->isAdmin())
                <form action="{{ route('community.forum.reply.destroy', $reply) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="color:var(--text-muted);">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </form>
                @endif
                @endauth
            </div>
            <div style="color:var(--text-secondary);line-height:1.7;">{{ $reply->body }}</div>
        </div>
        @endforeach

        {{-- Form Reply --}}
        @auth
        <div class="card card-body mt-3">
            <h4 class="mb-3">Tulis Balasan</h4>
            <form action="{{ route('community.forum.reply', $forum) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="body" class="form-control" rows="4"
                        placeholder="Tulis balasanmu..." required>{{ old('body') }}</textarea>
                    @error('body')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-reply"></i> Kirim Balasan
                </button>
            </form>
        </div>
        @else
        <div class="card card-body mt-3 text-center" style="color:var(--text-muted);">
            <a href="{{ route('login') }}">Login</a> untuk membalas diskusi ini.
        </div>
        @endauth
    </div>
</div>
@endsection