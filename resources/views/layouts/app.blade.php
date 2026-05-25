<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameHub') | GameHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Exo+2:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="container nav-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="nav-logo">
                <i class="fa-solid fa-gamepad"></i> GameHub
            </a>

            {{-- Nav Links --}}
            <div class="nav-links">
                <a href="{{ route('games.index') }}" class="nav-link {{ request()->routeIs('games.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i> Store
                </a>
                @auth
                <a href="{{ route('library.index') }}" class="nav-link {{ request()->routeIs('library.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open"></i> Library
                </a>
                @endauth
                <a href="{{ route('community.forum.index') }}" class="nav-link {{ request()->routeIs('community.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Community
                </a>

                {{-- AJAX Live Search --}}
                <div style="position:relative;" id="search-wrapper">
                    <div style="display:flex;align-items:center;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:0.35rem 0.75rem;gap:0.5rem;">
                        <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted);font-size:0.85rem;"></i>
                        <input type="text" id="live-search" placeholder="Cari game..."
                               style="background:transparent;border:none;outline:none;color:var(--text-primary);font-family:var(--font-body);font-size:0.875rem;width:180px;">
                    </div>
                    <div id="search-results"
                         style="display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;min-width:280px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);z-index:9999;overflow:hidden;box-shadow:var(--shadow);">
                    </div>
                </div>
            </div>{{-- ✅ Tutup nav-links di sini --}}

            {{-- Nav Actions --}}
            <div class="nav-actions">
                @auth
                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="nav-icon-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                    <span class="badge">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- User Dropdown --}}
                <div class="dropdown">
                    <button class="nav-user-btn">
                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="{{ auth()->user()->name }}"
                             class="nav-avatar">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="fa-solid fa-user"></i> Profil
                        </a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item">
                            <i class="fa-solid fa-receipt"></i> Riwayat Transaksi
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="dropdown-item">
                            <i class="fa-solid fa-heart"></i> Wishlist
                        </a>
                        @if(auth()->user()->isAdmin())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item text-accent">
                            <i class="fa-solid fa-gauge"></i> Admin Panel
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>{{-- ✅ Tutup nav-actions --}}

        </div>{{-- ✅ Tutup nav-inner --}}
    </nav>

    {{-- Alert Messages --}}
    <div class="alert-container">
        @foreach(['success', 'error', 'info', 'warning'] as $type)
        @if(session($type))
        <div class="alert alert-{{ $type }}">
            <i class="fa-solid fa-{{ $type === 'success' ? 'circle-check' : ($type === 'error' ? 'circle-xmark' : 'circle-info') }}"></i>
            {{ session($type) }}
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
        @endif
        @endforeach
    </div>

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand"><i class="fa-solid fa-gamepad"></i> GameHub</div>
                    <p class="footer-desc">Platform distribusi game digital global. Beli, hadiah, dan kelola koleksi game-mu.</p>
                </div>
                <div>
                    <h4 class="footer-title">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('games.index') }}">Store</a></li>
                        @auth
                        <li><a href="{{ route('library.index') }}">Library</a></li>
                        <li><a href="{{ route('cart.index') }}">Keranjang</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Akun</h4>
                    <ul class="footer-links">
                        @auth
                        <li><a href="{{ route('profile.index') }}">Profil Saya</a></li>
                        <li><a href="{{ route('orders.index') }}">Transaksi</a></li>
                        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                        @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} GameHub — UAS Project Laravel 12. Dibuat dengan <i class="fa-solid fa-heart text-accent"></i></p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

    <script>
        // Dropdown toggle
        document.querySelectorAll('.dropdown').forEach(d => {
            d.querySelector('.nav-user-btn')?.addEventListener('click', e => {
                e.stopPropagation();
                d.classList.toggle('open');
            });
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
        });

        // Auto dismiss alert setelah 4 detik
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        // AJAX Live Search
        const searchInput   = document.getElementById('live-search');
        const searchResults = document.getElementById('search-results');
        let searchTimeout   = null;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const q = this.value.trim();
                clearTimeout(searchTimeout);

                if (q.length < 2) {
                    searchResults.style.display = 'none';
                    searchResults.innerHTML = '';
                    return;
                }

                searchResults.style.display = 'block';
                searchResults.innerHTML = `
                    <div style="padding:1rem;text-align:center;color:var(--text-muted);font-size:0.85rem;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Mencari...
                    </div>`;

                searchTimeout = setTimeout(() => {
                    fetch(`/api/games/search?q=${encodeURIComponent(q)}`)
                        .then(r => r.json())
                        .then(games => {
                            if (games.length === 0) {
                                searchResults.innerHTML = `
                                    <div style="padding:1rem;text-align:center;color:var(--text-muted);font-size:0.85rem;">
                                        <i class="fa-solid fa-ghost"></i> Game tidak ditemukan
                                    </div>`;
                                return;
                            }

                            searchResults.innerHTML = games.map(g => `
                                <a href="/store/${g.slug}"
                                   style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 1rem;color:var(--text-primary);text-decoration:none;border-bottom:1px solid var(--border);"
                                   onmouseover="this.style.background='var(--bg-hover)'"
                                   onmouseout="this.style.background='transparent'">
                                    <img src="${g.cover_url}"
                                         style="width:48px;height:30px;object-fit:cover;border-radius:4px;flex-shrink:0;"
                                         onerror="this.src='https://via.placeholder.com/48x30/1a1a2e/e94560?text=G'">
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            ${g.title}
                                        </div>
                                        <div style="font-size:0.75rem;color:var(--accent);font-family:'Rajdhani',sans-serif;font-weight:700;">
                                            ${g.price}
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right" style="color:var(--text-muted);font-size:0.75rem;flex-shrink:0;"></i>
                                </a>
                            `).join('') + `
                                <a href="/store?search=${encodeURIComponent(q)}"
                                   style="display:block;padding:0.65rem 1rem;text-align:center;font-size:0.8rem;color:var(--text-secondary);border-top:1px solid var(--border);text-decoration:none;"
                                   onmouseover="this.style.background='var(--bg-hover)'"
                                   onmouseout="this.style.background='transparent'">
                                    Lihat semua hasil untuk "<strong>${q}</strong>"
                                </a>`;
                        })
                        .catch(() => {
                            searchResults.innerHTML = `
                                <div style="padding:1rem;text-align:center;color:#ff4757;font-size:0.85rem;">
                                    <i class="fa-solid fa-circle-xmark"></i> Gagal mencari
                                </div>`;
                        });
                }, 400);
            });

            document.addEventListener('click', function (e) {
                if (!document.getElementById('search-wrapper').contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    window.location.href = `/store?search=${encodeURIComponent(this.value.trim())}`;
                }
            });
        }
    </script>

</body>
</html>