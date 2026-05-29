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
    <style>
        .nav-menu-toggle {
            display: none;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .mobile-menu {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 999;
        }
        .mobile-menu.open { display: block; }
        .mobile-menu-inner {
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            width: 280px;
            height: 100%;
            padding: 1rem 0;
            overflow-y: auto;
        }
        .mobile-menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: var(--text-secondary);
            font-size: 1rem;
            font-family: var(--font-body);
            border: none;
            background: none;
            width: 100%;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        .mobile-menu-link:hover,
        .mobile-menu-link.active {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-left: 3px solid var(--accent);
        }
        .mobile-menu-link i { width: 20px; text-align: center; color: var(--accent); }
        .mobile-menu-divider { border: none; border-top: 1px solid var(--border); margin: 0.5rem 0; }
        .mobile-menu-section { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); padding: 0.75rem 1.5rem 0.3rem; }
        .mobile-menu-user { display: flex; align-items: center; gap: 0.75rem; padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); margin-bottom: 0.5rem; }
        .mobile-menu-user img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent); }
        .mobile-menu-user-name { font-weight: 600; color: var(--text-primary); font-size: 0.95rem; }
        .mobile-menu-user-email { font-size: 0.8rem; color: var(--text-muted); }

        /* Live Search */
        #live-search-wrap { position: relative; margin-left: 0.5rem; }
        #live-search {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: 0.4rem 0.85rem;
            font-family: var(--font-body);
            font-size: 0.85rem;
            width: 200px;
            outline: none;
            transition: var(--transition);
        }
        #live-search:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,0.2);
        }
        #live-search::placeholder { color: var(--text-muted); }
        #search-results {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 300px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            z-index: 9999;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            overflow: hidden;
        }
        .search-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.7rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }
        .search-item:hover { background: var(--bg-hover); }
        .search-item-title { font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        .search-item-price { color: var(--accent); font-family: var(--font-display); font-size: 0.9rem; font-weight: 700; flex-shrink: 0; margin-left: 0.5rem; }
        .search-see-all {
            display: block;
            padding: 0.65rem 1rem;
            text-align: center;
            color: var(--accent);
            font-size: 0.85rem;
            text-decoration: none;
            border-top: 1px solid var(--border);
            transition: var(--transition);
        }
        .search-see-all:hover { background: var(--bg-hover); color: var(--accent); }
        .search-empty { padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.85rem; }

        @media (max-width: 768px) {
            .nav-links { display: none !important; }
            .nav-actions .btn { display: none; }
            .nav-actions .nav-icon-btn { display: flex; }
            .nav-actions .dropdown { display: flex; }
            .nav-menu-toggle { display: flex; }
            .nav-user-btn span { display: none; }
            .nav-user-btn i.fa-chevron-down { display: none; }
            #live-search-wrap { display: none; }
        }
        @media (max-width: 480px) {
            .nav-logo { font-size: 1.2rem; }
            .mobile-menu-inner { width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            <i class="fa-solid fa-gamepad"></i> GameHub
        </a>

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

            {{-- Live Search --}}
            <div id="live-search-wrap">
                <input type="text" id="live-search" placeholder="Cari game...">
                <div id="search-results"></div>
            </div>
        </div>

        <div class="nav-actions">
            @auth
            <a href="{{ route('cart.index') }}" class="nav-icon-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                <span class="badge">{{ $cartCount }}</span>
                @endif
            </a>

            <div class="dropdown">
                <button class="nav-user-btn">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="nav-avatar">
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

            <button class="nav-menu-toggle" id="menu-toggle">
                <i class="fa-solid fa-bars" id="menu-icon"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu Drawer --}}
<div class="mobile-menu" id="mobile-menu">
    <div class="mobile-menu-inner">
        @auth
        <div class="mobile-menu-user">
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
            <div>
                <div class="mobile-menu-user-name">{{ auth()->user()->name }}</div>
                <div class="mobile-menu-user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        @endauth

        <div class="mobile-menu-section">Menu</div>
        <a href="{{ route('home') }}" class="mobile-menu-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i> Beranda
        </a>
        <a href="{{ route('games.index') }}" class="mobile-menu-link {{ request()->routeIs('games.*') ? 'active' : '' }}">
            <i class="fa-solid fa-store"></i> Store
        </a>
        @auth
        <a href="{{ route('library.index') }}" class="mobile-menu-link {{ request()->routeIs('library.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book-open"></i> Library
        </a>
        <a href="{{ route('cart.index') }}" class="mobile-menu-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cart-shopping"></i> Keranjang
            @php $cartCount = count(session('cart', [])); @endphp
            @if($cartCount > 0)
            <span style="background:var(--accent);color:#fff;border-radius:999px;padding:0.1rem 0.5rem;font-size:0.7rem;margin-left:auto;">
                {{ $cartCount }}
            </span>
            @endif
        </a>
        @endauth
        <a href="{{ route('community.forum.index') }}" class="mobile-menu-link {{ request()->routeIs('community.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Community
        </a>

        @auth
        <hr class="mobile-menu-divider">
        <div class="mobile-menu-section">Akun</div>
        <a href="{{ route('profile.index') }}" class="mobile-menu-link">
            <i class="fa-solid fa-user"></i> Profil Saya
        </a>
        <a href="{{ route('orders.index') }}" class="mobile-menu-link">
            <i class="fa-solid fa-receipt"></i> Riwayat Transaksi
        </a>
        <a href="{{ route('wishlist.index') }}" class="mobile-menu-link">
            <i class="fa-solid fa-heart"></i> Wishlist
        </a>
        @if(auth()->user()->isAdmin())
        <hr class="mobile-menu-divider">
        <div class="mobile-menu-section">Admin</div>
        <a href="{{ route('admin.dashboard') }}" class="mobile-menu-link">
            <i class="fa-solid fa-gauge"></i> Admin Panel
        </a>
        @endif
        <hr class="mobile-menu-divider">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="mobile-menu-link" style="color:#ff4757;">
                <i class="fa-solid fa-right-from-bracket" style="color:#ff4757;"></i> Logout
            </button>
        </form>
        @else
        <hr class="mobile-menu-divider">
        <div style="padding:1rem 1.5rem;display:flex;flex-direction:column;gap:0.75rem;">
            <a href="{{ route('login') }}" class="btn btn-outline btn-block">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-block">
                <i class="fa-solid fa-user-plus"></i> Daftar
            </a>
        </div>
        @endauth
    </div>
</div>

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
                    <li><a href="{{ route('community.forum.index') }}">Community</a></li>
                    @auth
                    <li><a href="{{ route('library.index') }}">Library</a></li>
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

@stack('scripts')

<script>
// ── Dropdown Desktop ─────────────────────────────────────
document.querySelectorAll('.dropdown').forEach(d => {
    d.querySelector('.nav-user-btn')?.addEventListener('click', e => {
        e.stopPropagation();
        d.classList.toggle('open');
    });
});
document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
});

// ── Mobile Menu ──────────────────────────────────────────
const menuToggle = document.getElementById('menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const menuIcon   = document.getElementById('menu-icon');

if (menuToggle) {
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = mobileMenu.classList.toggle('open');
        menuIcon.className = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });

    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            mobileMenu.classList.remove('open');
            menuIcon.className = 'fa-solid fa-bars';
        }
    });

    document.querySelectorAll('.mobile-menu-link').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            menuIcon.className = 'fa-solid fa-bars';
        });
    });
}

// ── AJAX Live Search ─────────────────────────────────────
const liveSearch    = document.getElementById('live-search');
const searchResults = document.getElementById('search-results');

if (liveSearch && searchResults) {
    let searchTimeout = null;

    liveSearch.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(searchTimeout);

        if (q.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            searchResults.style.display = 'block';
            searchResults.innerHTML = `
                <div class="search-empty">
                    <i class="fa-solid fa-spinner fa-spin"></i> Mencari...
                </div>`;

            fetch(`/api/games/search?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(games => {
                    if (games.length === 0) {
                        searchResults.innerHTML = `
                            <div class="search-empty">
                                <i class="fa-solid fa-ghost"></i> Game tidak ditemukan
                            </div>`;
                        return;
                    }

                    const items = games.map(g => `
                        <a href="/store/${g.slug}" class="search-item">
                            <div class="search-item-title">
                                <i class="fa-solid fa-gamepad" style="color:var(--accent);font-size:0.8rem;"></i>
                                ${g.title}
                            </div>
                            <span class="search-item-price">
                                ${parseInt(g.price) === 0 ? 'GRATIS' : 'Rp ' + parseInt(g.price).toLocaleString('id-ID')}
                            </span>
                        </a>
                    `).join('');

                    const seeAll = `
                        <a href="/store?search=${encodeURIComponent(q)}" class="search-see-all">
                            Lihat semua hasil untuk "${q}" <i class="fa-solid fa-arrow-right"></i>
                        </a>`;

                    searchResults.innerHTML = items + seeAll;
                })
                .catch(() => {
                    searchResults.innerHTML = `
                        <div class="search-empty">
                            <i class="fa-solid fa-circle-exclamation"></i> Gagal memuat hasil
                        </div>`;
                });
        }, 300);
    });

    liveSearch.addEventListener('focus', function () {
        if (this.value.trim().length >= 2 && searchResults.innerHTML !== '') {
            searchResults.style.display = 'block';
        }
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#live-search-wrap')) {
            searchResults.style.display = 'none';
        }
    });
}

// ── Auto Dismiss Alert ───────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>

</body>
</html>