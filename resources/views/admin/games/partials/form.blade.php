<div class="form-group">
  <label class="form-label">Judul Game *</label>
  <input type="text" name="title" class="form-control"
    value="{{ old('title', $game->title ?? '') }}" required>
  @error('title')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="grid-2">
  <div class="form-group">
    <label class="form-label">Developer *</label>
    <input type="text" name="developer" class="form-control"
      value="{{ old('developer', $game->developer ?? '') }}" required>
    @error('developer')<span class="form-error">{{ $message }}</span>@enderror
  </div>
  <div class="form-group">
    <label class="form-label">Publisher *</label>
    <input type="text" name="publisher" class="form-control"
      value="{{ old('publisher', $game->publisher ?? '') }}" required>
    @error('publisher')<span class="form-error">{{ $message }}</span>@enderror
  </div>
</div>

<div class="form-group">
  <label class="form-label">Deskripsi *</label>
  <textarea name="description" class="form-control" rows="4" required>{{ old('description', $game->description ?? '') }}</textarea>
  @error('description')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="grid-2">
  <div class="form-group">
    <label class="form-label">Harga (Rp) *</label>
    <input type="number" name="price" class="form-control" min="0" step="1000"
      value="{{ old('price', $game->price ?? 0) }}" required>
    @error('price')<span class="form-error">{{ $message }}</span>@enderror
  </div>
  <div class="form-group">
    <label class="form-label">Stok</label>
    <input type="number" name="stock" class="form-control" min="0"
      value="{{ old('stock', $game->stock ?? 999) }}" required>
  </div>
</div>

<div class="grid-2">
  <div class="form-group">
    <label class="form-label">Kategori *</label>
    <select name="category_id" class="form-control" required>
      <option value="">-- Pilih Kategori --</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->id }}"
        {{ old('category_id', $game->category_id ?? '') == $cat->id ? 'selected' : '' }}>
        {{ $cat->name }}
      </option>
      @endforeach
    </select>
    @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
  </div>
  <div class="form-group">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
      <option value="active" {{ old('status', $game->status ?? 'active') === 'active'   ? 'selected' : '' }}>Aktif</option>
      <option value="inactive" {{ old('status', $game->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>
  </div>
</div>

<div class="grid-2">
  <div class="form-group">
    <label class="form-label">Tanggal Rilis</label>
    <input type="date" name="release_date" class="form-control"
      value="{{ old('release_date', isset($game->release_date) ? $game->release_date->format('Y-m-d') : '') }}">
  </div>
  <div class="form-group">
    <label class="form-label">URL Trailer (YouTube)</label>
    <input type="url" name="trailer_url" class="form-control" placeholder="https://..."
      value="{{ old('trailer_url', $game->trailer_url ?? '') }}">
  </div>
</div>

<div class="form-group">
  <label class="form-label">Cover Image</label>
  @if(isset($game) && $game->cover_image)
  <div style="margin-bottom:0.5rem;">
    <img src="{{ $game->cover_url }}" style="height:80px;border-radius:4px;">
    <span style="font-size:0.8rem;color:var(--text-muted);margin-left:0.5rem;">Cover saat ini</span>
  </div>
  @endif
  <input type="file" name="cover_image" class="form-control" accept="image/*">
  @error('cover_image')<span class="form-error">{{ $message }}</span>@enderror
</div>

<div class="form-group" style="margin-bottom:1.5rem;">
  <label class="form-label">Spesifikasi Sistem (JSON)</label>
  <textarea name="system_requirements" class="form-control" rows="3"
    placeholder='{"OS":"Windows 10","RAM":"8GB","GPU":"GTX 1060"}'>{{ old('system_requirements', isset($game->system_requirements) ? json_encode($game->system_requirements) : '') }}</textarea>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    // ── Helper ──────────────────────────────────────────────
    function showError(input, message) {
        clearError(input);
        input.style.borderColor = '#ff4757';
        const span = document.createElement('span');
        span.className    = 'form-error js-error';
        span.textContent  = message;
        input.parentNode.appendChild(span);
    }

    function clearError(input) {
        input.style.borderColor = '';
        const old = input.parentNode.querySelector('.js-error');
        if (old) old.remove();
    }

    function isValidUrl(string) {
        try { new URL(string); return true; } catch { return false; }
    }

    // ── Validasi per field ───────────────────────────────────
    function validateTitle() {
        const input = form.querySelector('[name="title"]');
        if (!input) return true;
        if (!input.value.trim()) {
            showError(input, 'Judul game wajib diisi.'); return false;
        }
        if (input.value.trim().length < 3) {
            showError(input, 'Judul game minimal 3 karakter.'); return false;
        }
        clearError(input); return true;
    }

    function validateDescription() {
        const input = form.querySelector('[name="description"]');
        if (!input) return true;
        if (!input.value.trim()) {
            showError(input, 'Deskripsi wajib diisi.'); return false;
        }
        if (input.value.trim().length < 20) {
            showError(input, 'Deskripsi minimal 20 karakter.'); return false;
        }
        clearError(input); return true;
    }

    function validateDeveloper() {
        const input = form.querySelector('[name="developer"]');
        if (!input) return true;
        if (!input.value.trim()) {
            showError(input, 'Developer wajib diisi.'); return false;
        }
        clearError(input); return true;
    }

    function validatePublisher() {
        const input = form.querySelector('[name="publisher"]');
        if (!input) return true;
        if (!input.value.trim()) {
            showError(input, 'Publisher wajib diisi.'); return false;
        }
        clearError(input); return true;
    }

    function validatePrice() {
        const input = form.querySelector('[name="price"]');
        if (!input) return true;
        if (input.value === '') {
            showError(input, 'Harga wajib diisi.'); return false;
        }
        if (isNaN(input.value) || Number(input.value) < 0) {
            showError(input, 'Harga harus berupa angka positif.'); return false;
        }
        clearError(input); return true;
    }

    function validateCategory() {
        const input = form.querySelector('[name="category_id"]');
        if (!input) return true;
        if (!input.value) {
            showError(input, 'Kategori wajib dipilih.'); return false;
        }
        clearError(input); return true;
    }

    function validateStock() {
        const input = form.querySelector('[name="stock"]');
        if (!input) return true;
        if (input.value === '' || isNaN(input.value) || Number(input.value) < 0) {
            showError(input, 'Stok harus berupa angka positif.'); return false;
        }
        clearError(input); return true;
    }

    function validateTrailerUrl() {
        const input = form.querySelector('[name="trailer_url"]');
        if (!input || !input.value.trim()) return true; // opsional
        if (!isValidUrl(input.value.trim())) {
            showError(input, 'URL trailer tidak valid.'); return false;
        }
        clearError(input); return true;
    }

    function validateCoverImage() {
        const input = form.querySelector('[name="cover_image"]');
        if (!input || !input.files.length) return true; // opsional
        const file      = input.files[0];
        const allowed   = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize   = 4 * 1024 * 1024; // 4MB
        if (!allowed.includes(file.type)) {
            showError(input, 'Format gambar harus jpg, png, atau webp.'); return false;
        }
        if (file.size > maxSize) {
            showError(input, 'Ukuran gambar maksimal 4MB.'); return false;
        }
        clearError(input); return true;
    }

    // ── Real-time validation (blur) ──────────────────────────
    form.querySelector('[name="title"]')?.addEventListener('blur', validateTitle);
    form.querySelector('[name="description"]')?.addEventListener('blur', validateDescription);
    form.querySelector('[name="developer"]')?.addEventListener('blur', validateDeveloper);
    form.querySelector('[name="publisher"]')?.addEventListener('blur', validatePublisher);
    form.querySelector('[name="price"]')?.addEventListener('blur', validatePrice);
    form.querySelector('[name="category_id"]')?.addEventListener('change', validateCategory);
    form.querySelector('[name="stock"]')?.addEventListener('blur', validateStock);
    form.querySelector('[name="trailer_url"]')?.addEventListener('blur', validateTrailerUrl);
    form.querySelector('[name="cover_image"]')?.addEventListener('change', validateCoverImage);

    // ── Submit validation ────────────────────────────────────
    form.addEventListener('submit', function (e) {
        const checks = [
            validateTitle(),
            validateDescription(),
            validateDeveloper(),
            validatePublisher(),
            validatePrice(),
            validateCategory(),
            validateStock(),
            validateTrailerUrl(),
            validateCoverImage(),
        ];

        if (checks.includes(false)) {
            e.preventDefault();
            // Scroll ke error pertama
            const firstError = form.querySelector('.js-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
@endpush