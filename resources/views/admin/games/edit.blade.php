@extends('layouts.app')
@section('title', 'Edit Game')
@section('content')
<div class="admin-layout">
  @include('admin.partials.sidebar')
  <div class="admin-content">
    <div class="flex items-center gap-2 mb-4">
      <a href="{{ route('admin.games.index') }}" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h1 style="font-size:1.5rem;">Edit: {{ $game->title }}</h1>
    </div>
    <div class="card card-body" style="max-width:750px;">
      <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.games.partials.form')
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-floppy-disk"></i> Update Game
        </button>
      </form>
    </div>
  </div>
</div>
@endsection