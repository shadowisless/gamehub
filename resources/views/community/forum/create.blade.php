@extends('layouts.app')
@section('title', 'Buat Diskusi')
@section('content')
<div class="section">
    <div class="container" style="max-width:700px;">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('community.forum.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 style="font-size:1.5rem;">Buat Diskusi Baru</h1>
        </div>

        <div class="card card-body">
            <form action="{{ route('community.forum.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Judul Diskusi *</label>
                    <input type="text" name="title" class="form-control"
                           placeholder="Judul diskusimu..." value="{{ old('title') }}" required>
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select name="category" class="form-control" required>
                            <option value="general"    {{ old('category') == 'general'    ? 'selected' : '' }}>General</option>
                            <option value="tips"       {{ old('category') == 'tips'       ? 'selected' : '' }}>Tips & Trick</option>
                            <option value="review"     {{ old('category') == 'review'     ? 'selected' : '' }}>Review</option>
                            <option value="bug_report" {{ old('category') == 'bug_report' ? 'selected' : '' }}>Bug Report</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Terkait Game (Opsional)</label>
                        <select name="game_id" class="form-control">
                            <option value="">-- Tidak ada --</option>
                            @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                {{ $game->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Isi Diskusi *</label>
                    <textarea name="body" class="form-control" rows="8"
                              placeholder="Tulis diskusimu di sini..." required>{{ old('body') }}</textarea>
                    @error('body')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-paper-plane"></i> Posting Diskusi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection