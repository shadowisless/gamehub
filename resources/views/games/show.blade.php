@extends('layouts.app')

@section('title', $game->title)

@section('content')
<div class="section">
    <div class="container">

        <div class="grid-2" style="grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
            {{-- Kiri: Info Game --}}
            <div>
                {{-- Cover --}}
                <img src="{{ $game->cover_url }}" alt="{{ $game->title }}"
                    style="width: 100%; border-radius: var(--radius-lg); margin-bottom: 1.5rem;"
                    onerror="this.src='https://via.placeholder.com/800x450/1a1a2e/e94560?text={{ urlencode($game->title) }}'">

                {{-- Deskripsi --}}
                <h1 style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $game->title }}</h1>
                <div style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    <span class="badge-pill badge-active">{{ $game->category->name ?? 'Uncategorized' }}</span>
                    &nbsp;·&nbsp; {{ $game->developer }} &nbsp;·&nbsp;
                    @if($game->release_date)
                    {{ $game->release_date->format('d M Y') }}
                    @endif
                </div>

                <div class="card card-body mb-3">
                    <h3 style="margin-bottom: 0.75rem;">Deskripsi</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8;">{{ $game->description }}</p>
                </div>

                @if($game->system_requirements)
                <div class="card card-body">
                    <h3 style="margin-bottom: 0.75rem;"><i class="fa-solid fa-desktop"></i> Spesifikasi Sistem</h3>
                    @if(is_array($game->system_requirements))
                    <table class="table">
                        @foreach($game->system_requirements as $key => $val)
                        <tr>
                            <td style="font-weight: 600; color: var(--text-primary); width: 35%;">{{ ucfirst($key) }}</td>
                            <td>{{ $val }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <p style="color: var(--text-secondary);">{{ $game->system_requirements }}</p>
                    @endif
                </div>
                @endif
            </div>

            {{-- Kanan: Action Card --}}
            <div style="position: sticky; top: 90px;">
                <div class="card card-body">
                    <div class="game-price" style="font-size: 2rem; margin-bottom: 1.5rem;">
                        {{ $game->price == 0 ? 'GRATIS' : $game->formatted_price }}
                    </div>

                    @auth
                    @if($userOwns)
                    <div class="btn btn-secondary btn-block btn-lg mb-3" style="cursor: default;">
                        <i class="fa-solid fa-check"></i> Sudah di Library
                    </div>
                    <a href="{{ route('library.index') }}" class="btn btn-outline btn-block">
                        <i class="fa-solid fa-book-open"></i> Buka Library
                    </a>
                    @else
                    <form action="{{ route('cart.add', $game) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="fa-solid fa-cart-shopping"></i> Tambah ke Keranjang
                        </button>
                    </form>
                    <form action="{{ route('wishlist.toggle', $game) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-block {{ $userInWishlist ? 'btn-danger' : 'btn-outline' }}">
                            <i class="fa-{{ $userInWishlist ? 'solid' : 'regular' }} fa-heart"></i>
                            {{ $userInWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                        </button>
                    </form>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block btn-lg mb-3">
                        <i class="fa-solid fa-right-to-bracket"></i> Login untuk Beli
                    </a>
                    @endauth

                    <div style="border-top: 1px solid var(--border); margin-top: 1.25rem; padding-top: 1.25rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                            <div class="flex justify-between">
                                <span>Developer</span>
                                <span style="color: var(--text-primary);">{{ $game->developer }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Publisher</span>
                                <span style="color: var(--text-primary);">{{ $game->publisher }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Genre</span>
                                <span style="color: var(--text-primary);">{{ $game->category->name ?? '-' }}</span>
                            </div>
                            @if($game->release_date)
                            <div class="flex justify-between">
                                <span>Rilis</span>
                                <span style="color: var(--text-primary);">{{ $game->release_date->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Trailer --}}
                @if($game->trailer_url)
                <div class="card card-body mt-3">
                    <h4 style="margin-bottom: 0.75rem;"><i class="fa-solid fa-film"></i> Trailer</h4>
                    <a href="{{ $game->trailer_url }}" target="_blank" class="btn btn-outline btn-block">
                        <i class="fa-brands fa-youtube"></i> Tonton Trailer
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Game Serupa --}}
        @if($relatedGames->count() > 0)
        <div class="mt-4">
            <h2 class="section-title mb-3">Game <span>Serupa</span></h2>
            <div class="games-grid">
                @foreach($relatedGames as $related)
                <a href="{{ route('games.show', $related->slug) }}" class="card game-card">
                    <img src="{{ $related->cover_url }}" alt="{{ $related->title }}" class="game-card-img"
                        onerror="this.src='https://via.placeholder.com/320x180/1a1a2e/e94560?text={{ urlencode($related->title) }}'">
                    <div class="game-card-body">
                        <div class="game-card-title">{{ $related->title }}</div>
                        <div class="game-card-footer">
                            <span class="game-price">{{ $related->formatted_price }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ✅ Reviews — dipindah ke dalam .container agar layout konsisten --}}
        <div class="mt-4">
            <h2 class="section-title mb-3">Review <span>Pemain</span></h2>

            {{-- Form Tulis Review --}}
            @auth
            @if($userOwns && !auth()->user()->hasReviewed($game->id))
            <div class="card card-body mb-3">
                <h4 class="mb-3">Tulis Reviewmu</h4>
                <form action="{{ route('review.store', $game) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Rating *</label>
                        <div style="display:flex; gap:0.5rem;" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <label style="cursor:pointer; font-size:1.75rem; color:var(--text-muted);" class="star-label">
                                <input type="radio" name="rating" value="{{ $i }}"
                                    {{ old('rating') == $i ? 'checked' : '' }}
                                    style="display:none;" class="star-input">
                                ★
                                </label>
                                @endfor
                        </div>
                        @error('rating')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Judul Review (Opsional)</label>
                        <input type="text" name="title" class="form-control"
                            placeholder="Ringkasan reviewmu..." value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Review *</label>
                        <textarea name="body" class="form-control" rows="4"
                            placeholder="Bagikan pengalamanmu bermain game ini..." required>{{ old('body') }}</textarea>
                        @error('body')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-star"></i> Kirim Review
                    </button>
                </form>
            </div>
            @elseif($userOwns && auth()->user()->hasReviewed($game->id))
            <div class="card card-body mb-3" style="border-color: var(--border-accent);">
                <p style="color: var(--text-secondary);">
                    <i class="fa-solid fa-circle-check" style="color: #2ed573;"></i>
                    Kamu sudah memberikan review untuk game ini.
                </p>
            </div>
            @elseif(!$userOwns)
            <div class="card card-body mb-3" style="color: var(--text-muted); text-align: center;">
                <i class="fa-solid fa-lock" style="margin-right: 0.4rem;"></i>
                Beli game ini terlebih dahulu untuk menulis review.
            </div>
            @endif
            @else
            <div class="card card-body mb-3" style="color: var(--text-muted); text-align: center;">
                <a href="{{ route('login') }}">Login</a> untuk menulis review.
            </div>
            @endauth

            {{-- Daftar Review --}}
            @php
            $reviews = $game->reviews()->with('user')->latest()->get();
            $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;
            @endphp

            @if($reviews->count() > 0)
            {{-- Rating Summary --}}
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; padding:1.25rem; background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg);">
                <div style="font-family:var(--font-display); font-size:3.5rem; font-weight:700; color:var(--accent); line-height:1;">
                    {{ $avgRating }}
                </div>
                <div>
                    <div style="color:#ffa502; font-size:1.3rem; letter-spacing:2px;">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= round($avgRating) ? '★' : '☆' }}
                            @endfor
                            </div>
                            <div style="color:var(--text-muted); font-size:0.85rem; margin-top:0.25rem;">
                                Berdasarkan {{ $reviews->count() }} review
                            </div>
                    </div>
                </div>

                {{-- List Review --}}
                @foreach($reviews as $review)
                <div class="card card-body mb-2">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem;">
                        <img src="{{ $review->user->avatar_url }}"
                            style="width:36px; height:36px; border-radius:50%; flex-shrink:0;">
                        <div style="flex:1;">
                            <div style="font-weight:600; color:var(--text-primary);">{{ $review->user->name }}</div>
                            <div style="color:#ffa502; font-size:0.9rem;">{{ $review->stars }}</div>
                        </div>
                        <div style="font-size:0.8rem; color:var(--text-muted);">
                            {{ $review->created_at->diffForHumans() }}
                        </div>
                        @auth
                        @if(auth()->id() === $review->user_id || auth()->user()->isAdmin())
                        <form action="{{ route('review.destroy', $review) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color:var(--text-muted);"
                                onclick="return confirm('Hapus review ini?')">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                        @endif
                        @endauth
                    </div>

                    @if($review->title)
                    <div style="font-weight:600; color:var(--text-primary); margin-bottom:0.3rem;">
                        {{ $review->title }}
                    </div>
                    @endif
                    <div style="color:var(--text-secondary); line-height:1.7;">{{ $review->body }}</div>
                </div>
                @endforeach

                @else
                <div class="card card-body text-center" style="color:var(--text-muted); padding: 2rem;">
                    <i class="fa-regular fa-star" style="font-size:2rem; margin-bottom:0.5rem; display:block;"></i>
                    Belum ada review. Jadilah yang pertama!
                </div>
                @endif

            </div>{{-- end reviews --}}

        </div>{{-- end container --}}
    </div>{{-- end section --}}

    @push('scripts')
    <script>
        // Star rating interaktif
        const labels = document.querySelectorAll('.star-label');

        labels.forEach((label, index) => {
            label.addEventListener('mouseover', () => {
                labels.forEach((l, i) => {
                    l.style.color = i <= index ? '#ffa502' : 'var(--text-muted)';
                });
            });

            label.addEventListener('mouseleave', () => {
                updateStars();
            });

            label.addEventListener('click', () => {
                // Beri sedikit delay agar radio berubah dulu
                setTimeout(updateStars, 10);
            });
        });

        function updateStars() {
            const checked = document.querySelector('.star-input:checked');
            const val = checked ? parseInt(checked.value) : 0;
            labels.forEach((l, i) => {
                l.style.color = i < val ? '#ffa502' : 'var(--text-muted)';
            });
        }

        updateStars();
    </script>
    @endpush