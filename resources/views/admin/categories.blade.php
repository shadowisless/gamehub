@extends('layouts.app')
@section('title', 'Admin — Kategori')
@section('content')
<div class="admin-layout">
  @include('admin.partials.sidebar')
  <div class="admin-content">
    <h1 style="font-size:1.75rem;margin-bottom:1.5rem;">Kelola Kategori</h1>
    <div class="grid-2" style="align-items:start;">

      {{-- Form Tambah --}}
      <div class="card card-body">
        <h3 class="mb-3">Tambah Kategori Baru</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Action" required>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Deskripsi (Opsional)</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah
          </button>
        </form>
      </div>

      {{-- Daftar Kategori --}}
      <div class="card overflow-x-auto">
        <table class="table">
          <thead>
            <tr><th>Nama</th><th>Slug</th><th>Game</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            @foreach($categories as $cat)
            <tr>
              <td style="color:var(--text-primary);font-weight:600;">{{ $cat->name }}</td>
              <td style="color:var(--text-muted);font-size:0.85rem;">{{ $cat->slug }}</td>
              <td>{{ $cat->games_count }}</td>
              <td>
                @if($cat->games_count === 0)
                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="delete-form">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm delete-btn">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
                @else
                <span style="font-size:0.8rem;color:var(--text-muted);">Tidak bisa dihapus</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
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