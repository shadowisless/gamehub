@extends('layouts.app')
@section('title', '403 Forbidden')
@section('content')
<div class="text-center" style="padding:8rem 0;">
  <div style="font-size:5rem;color:var(--accent);font-family:var(--font-display);">403</div>
  <h2 style="color:var(--text-secondary);margin-bottom:1rem;">Akses Ditolak</h2>
  <p style="color:var(--text-muted);margin-bottom:2rem;">Kamu tidak punya izin untuk mengakses halaman ini.</p>
  <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection