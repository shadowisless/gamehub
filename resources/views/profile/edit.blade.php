@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="section">
    <div class="container" style="max-width: 700px;">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('profile.index') }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 style="font-size: 1.5rem;">Edit Profil</h1>
        </div>

        {{-- Update Info --}}
        <div class="card card-body mb-3">
            <h3 class="mb-3">Informasi Akun</h3>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')

                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                        <img src="{{ auth()->user()->avatar_url }}" id="avatar-preview"
                             style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border);"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1a1a2e&color=e94560&size=64'">
                        <input type="file" name="avatar" accept="image/*" class="form-control" id="avatar-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    @error('username')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="card card-body">
            <h3 class="mb-3">Ganti Password</h3>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf @method('PATCH')

                <div class="form-group">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Min 8 karakter" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-key"></i> Perbarui Password
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
    reader.readAsDataURL(this.files[0]);
});
</script>
@endpush
@endsection