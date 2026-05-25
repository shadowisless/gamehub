@extends('layouts.app')

@section('title', 'Admin — Users')

@section('content')
<div class="admin-layout">

    {{-- Sidebar --}}
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

    {{-- Content --}}
    <div class="admin-content">
        <div class="flex justify-between items-center mb-4">
            <h1 style="font-size:1.75rem;">Kelola Users</h1>
            <span style="color:var(--text-secondary);font-size:0.9rem;">
                Total: {{ $users->total() }} user
            </span>
        </div>

        <div class="card overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Game</th>
                        <th>Order</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $loop->iteration }}</td>
                        <td style="color:var(--text-primary);font-weight:600;">{{ $u->name }}</td>
                        <td style="color:var(--text-secondary);">{{ '@' . $u->username }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge-pill {{ $u->role === 'admin' ? 'badge-gift' : 'badge-active' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>{{ $u->library_games_count }}</td>
                        <td>{{ $u->orders_count }}</td>
                        <td>{{ $u->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center" style="color:var(--text-muted);padding:3rem;">
                            Belum ada user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $users->links() }}</div>
    </div>

</div>
@endsection