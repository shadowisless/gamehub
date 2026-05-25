@extends('layouts.app')
@section('title', '404 Not Found')
@section('content')
<div class="text-center" style="padding:8rem 0;">
  <div style="font-size:5rem;color:var(--accent);font-family:var(--font-display);">404</div>
  <h2 style="color:var(--text-secondary);margin-bottom:1rem;">Halaman Tidak Ditemukan</h2>
  <p style="color:var(--text-muted);margin-bottom:2rem;">Halaman yang kamu cari tidak ada atau sudah dipindahkan.</p>
  <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection