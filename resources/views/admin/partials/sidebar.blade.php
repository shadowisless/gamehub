<aside class="sidebar">
  <div style="padding:1.25rem;border-bottom:1px solid var(--border);margin-bottom:0.5rem;">
    <div style="font-family:var(--font-display);font-weight:700;font-size:1rem;color:var(--accent);">
      <i class="fa-solid fa-gauge"></i> Admin Panel
    </div>
  </div>
  <div class="sidebar-title">Utama</div>
  <a href="{{ route('admin.dashboard') }}"
     class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-line"></i> Dashboard
  </a>
  <div class="sidebar-title">Konten</div>
  <a href="{{ route('admin.games.index') }}"
     class="sidebar-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
    <i class="fa-solid fa-gamepad"></i> Games
  </a>
  <a href="{{ route('admin.categories') }}"
     class="sidebar-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
    <i class="fa-solid fa-tags"></i> Kategori
  </a>
  <div class="sidebar-title">Pengguna</div>
  <a href="{{ route('admin.users') }}"
     class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
    <i class="fa-solid fa-users"></i> Users
  </a>
  <a href="{{ route('admin.orders') }}"
     class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
    <i class="fa-solid fa-receipt"></i> Orders
  </a>
  <div style="border-top:1px solid var(--border);margin-top:1rem;padding-top:1rem;">
    <a href="{{ route('home') }}" class="sidebar-link">
      <i class="fa-solid fa-arrow-left"></i> Kembali ke Site
    </a>
  </div>
</aside>
