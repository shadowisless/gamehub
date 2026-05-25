@extends('layouts.app')
@section('title', 'Admin — Kelola Game')
@section('content')
<div class="admin-layout">
  @include('admin.partials.sidebar')
  <div class="admin-content">
    <div class="flex justify-between items-center mb-4">
      <h1 style="font-size:1.75rem;">Kelola Game</h1>
      <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Game
      </a>
    </div>

    <form action="{{ route('admin.games.index') }}" method="GET" class="filter-bar mb-3">
      <input type="text" name="search" class="form-control" placeholder="Cari game..." value="{{ request('search') }}" style="flex:1;">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
    </form>

    <div class="card overflow-x-auto">
      <table class="table">
        <thead>
          <tr>
            <th>Cover</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($games as $game)
          <tr>
            <td>
              <img src="{{ $game->cover_url }}" style="width:60px;height:38px;object-fit:cover;border-radius:4px;"
                onerror="this.src='https://via.placeholder.com/60x38/1a1a2e/e94560?text=G'">
            </td>
            <td style="color:var(--text-primary);font-weight:600;">{{ $game->title }}</td>
            <td>{{ $game->category->name ?? '-' }}</td>
            <td style="color:var(--accent);">{{ $game->formatted_price }}</td>
            <td>
              <span class="badge-pill {{ $game->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                {{ ucfirst($game->status) }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-outline btn-sm">
                  <i class="fa-solid fa-pen"></i>
                </a>
                <form action="{{ route('admin.games.destroy', $game) }}" method="POST" class="delete-form">
                  @csrf @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm delete-btn">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center" style="color:var(--text-muted);">Belum ada game.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $games->links() }}</div>
  </div>
</div>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e94560',
            cancelButtonColor: '#1a1a2e',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#1a1a2e',
            color: '#f0f0f0',
        }).then((result) => {
            if (result.isConfirmed) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endsection