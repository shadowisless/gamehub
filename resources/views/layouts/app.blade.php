<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Gunakan simple pagination agar tidak butuh Tailwind --}}
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
            </div>

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
                        <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="avatar" class="nav-avatar"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1a1a2e&color=e94560&size=40'">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.index') }}" class="dropdown-item"><i class="fa-solid fa-user"></i> Profil</a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item"><i class="fa-solid fa-receipt"></i> Riwayat Transaksi</a>
                        <a href="{{ route('wishlist.index') }}" class="dropdown-item"><i class="fa-solid fa-heart"></i> Wishlist</a>
                        @if(auth()->user()->isAdmin())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item text-accent"><i class="fa-solid fa-gauge"></i> Admin Panel</a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
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