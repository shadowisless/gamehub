@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div style="min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 460px;">
        <div class="text-center mb-4">
            <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fa-solid fa-gamepad" style="color: var(--accent);"></i> GameHub
            </div>
            <h2 style="font-size: 1.5rem; color: var(--text-secondary);">Buat Akun Baru</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama kamu"
                               value="{{ old('name') }}" required>
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="username_kamu"
                               value="{{ old('username') }}" required>
                        @error('username')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="kamu@email.com"
                               value="{{ old('email') }}" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 karakter" required>
                        @error('password')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fa-solid fa-user-plus"></i> Buat Akun
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center mt-3" style="color: var(--text-secondary); font-size: 0.9rem;">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </p>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    function showError(input, msg) {
        clearError(input);
        input.style.borderColor = '#ff4757';
        const span = document.createElement('span');
        span.className   = 'form-error js-error';
        span.textContent = msg;
        input.parentNode.appendChild(span);
    }

    function clearError(input) {
        input.style.borderColor = '';
        input.parentNode.querySelector('.js-error')?.remove();
    }

    const name     = form.querySelector('[name="name"]');
    const username = form.querySelector('[name="username"]');
    const email    = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');
    const confirm  = form.querySelector('[name="password_confirmation"]');

    // Real-time
    name.addEventListener('blur', () => {
        if (!name.value.trim())           showError(name, 'Nama wajib diisi.');
        else if (name.value.length < 3)   showError(name, 'Nama minimal 3 karakter.');
        else                              clearError(name);
    });

    username.addEventListener('blur', () => {
        const re = /^[a-zA-Z0-9_-]+$/;
        if (!username.value.trim())            showError(username, 'Username wajib diisi.');
        else if (username.value.length < 3)    showError(username, 'Username minimal 3 karakter.');
        else if (!re.test(username.value))     showError(username, 'Username hanya boleh huruf, angka, _ dan -.');
        else                                   clearError(username);
    });

    email.addEventListener('blur', () => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim())    showError(email, 'Email wajib diisi.');
        else if (!re.test(email.value)) showError(email, 'Format email tidak valid.');
        else                        clearError(email);
    });

    password.addEventListener('blur', () => {
        if (!password.value)              showError(password, 'Password wajib diisi.');
        else if (password.value.length < 8) showError(password, 'Password minimal 8 karakter.');
        else if (!/[a-zA-Z]/.test(password.value)) showError(password, 'Password harus mengandung huruf.');
        else if (!/[0-9]/.test(password.value))    showError(password, 'Password harus mengandung angka.');
        else                              clearError(password);
    });

    confirm.addEventListener('blur', () => {
        if (confirm.value !== password.value) showError(confirm, 'Konfirmasi password tidak cocok.');
        else                                  clearError(confirm);
    });

    // Submit
    form.addEventListener('submit', function (e) {
        let valid = true;

        if (!name.value.trim() || name.value.length < 3) {
            showError(name, 'Nama minimal 3 karakter.'); valid = false;
        }
        const re = /^[a-zA-Z0-9_-]+$/;
        if (!username.value.trim() || !re.test(username.value) || username.value.length < 3) {
            showError(username, 'Username tidak valid.'); valid = false;
        }
        const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRe.test(email.value)) {
            showError(email, 'Format email tidak valid.'); valid = false;
        }
        if (password.value.length < 8 || !/[0-9]/.test(password.value)) {
            showError(password, 'Password minimal 8 karakter dan mengandung angka.'); valid = false;
        }
        if (confirm.value !== password.value) {
            showError(confirm, 'Konfirmasi password tidak cocok.'); valid = false;
        }

        if (!valid) {
            e.preventDefault();
            form.querySelector('.js-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush
@endsection