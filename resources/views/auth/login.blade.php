@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div style="min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fa-solid fa-gamepad" style="color: var(--accent);"></i> GameHub
            </div>
            <h2 style="font-size: 1.5rem; color: var(--text-secondary);">Masuk ke Akunmu</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="kamu@email.com"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        @error('password')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex items-center justify-between mb-3">
                        <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: var(--text-secondary); cursor: pointer;">
                            <input type="checkbox" name="remember"> Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center mt-3" style="color: var(--text-secondary); font-size: 0.9rem;">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection