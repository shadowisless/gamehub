<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $games = $query->latest()->paginate(15)->withQueryString();
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // ✅ SERVER-SIDE VALIDATION #1 — Tambah Game
        $request->validate([
            'title'               => ['required', 'string', 'min:3', 'max:255', 'unique:games,title'],
            'description'         => ['required', 'string', 'min:20'],
            'developer'           => ['required', 'string', 'max:255'],
            'publisher'           => ['required', 'string', 'max:255'],
            'price'               => ['required', 'numeric', 'min:0', 'max:9999999'],
            'category_id'         => ['required', 'exists:categories,id'],
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'trailer_url'         => ['nullable', 'url'],
            'release_date'        => ['nullable', 'date', 'before_or_equal:today'],
            'status'              => ['required', 'in:active,inactive'],
            'stock'               => ['required', 'integer', 'min:0', 'max:99999'],
            'system_requirements' => ['nullable', 'string'],
        ], [
            // Pesan error custom (Bahasa Indonesia)
            'title.required'       => 'Judul game wajib diisi.',
            'title.min'            => 'Judul game minimal 3 karakter.',
            'title.unique'         => 'Judul game sudah terdaftar.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'developer.required'   => 'Nama developer wajib diisi.',
            'publisher.required'   => 'Nama publisher wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'cover_image.image'    => 'File harus berupa gambar.',
            'cover_image.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'cover_image.max'      => 'Ukuran gambar maksimal 4MB.',
            'trailer_url.url'      => 'URL trailer harus berupa URL yang valid.',
            'release_date.before_or_equal' => 'Tanggal rilis tidak boleh di masa depan.',
            'stock.min'            => 'Stok tidak boleh negatif.',
        ]);

        $data         = $request->except(['cover_image', 'system_requirements']);
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('cover_image')) {
            $filename           = time() . '_' . $request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->storeAs('covers', $filename, 'public');
            $data['cover_image'] = $filename;
        }

        if ($request->filled('system_requirements')) {
            $decoded = json_decode($request->system_requirements, true);
            $data['system_requirements'] = $decoded ?? ['info' => $request->system_requirements];
        }

        Game::create($data);

        return redirect()->route('admin.games.index')
                         ->with('success', 'Game berhasil ditambahkan!');
    }

    public function edit(Game $game)
    {
        $categories = Category::all();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        // ✅ SERVER-SIDE VALIDATION #2 — Edit Game
        $request->validate([
            'title'               => ['required', 'string', 'min:3', 'max:255', 'unique:games,title,' . $game->id],
            'description'         => ['required', 'string', 'min:20'],
            'developer'           => ['required', 'string', 'max:255'],
            'publisher'           => ['required', 'string', 'max:255'],
            'price'               => ['required', 'numeric', 'min:0', 'max:9999999'],
            'category_id'         => ['required', 'exists:categories,id'],
            'cover_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'trailer_url'         => ['nullable', 'url'],
            'release_date'        => ['nullable', 'date', 'before_or_equal:today'],
            'status'              => ['required', 'in:active,inactive'],
            'stock'               => ['required', 'integer', 'min:0', 'max:99999'],
        ], [
            'title.required'       => 'Judul game wajib diisi.',
            'title.min'            => 'Judul game minimal 3 karakter.',
            'title.unique'         => 'Judul game sudah terdaftar.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'developer.required'   => 'Nama developer wajib diisi.',
            'publisher.required'   => 'Nama publisher wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'cover_image.image'    => 'File harus berupa gambar.',
            'cover_image.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'cover_image.max'      => 'Ukuran gambar maksimal 4MB.',
            'stock.min'            => 'Stok tidak boleh negatif.',
        ]);

        $data = $request->except(['cover_image', 'system_requirements', '_method', '_token']);

        if ($request->hasFile('cover_image')) {
            if ($game->cover_image) {
                Storage::disk('public')->delete('covers/' . $game->cover_image);
            }
            $filename           = time() . '_' . $request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->storeAs('covers', $filename, 'public');
            $data['cover_image'] = $filename;
        }

        $game->update($data);

        return redirect()->route('admin.games.index')
                         ->with('success', 'Game berhasil diperbarui!');
    }

    public function destroy(Game $game)
    {
        if ($game->cover_image) {
            Storage::disk('public')->delete('covers/' . $game->cover_image);
        }

        $game->delete();

        return redirect()->route('admin.games.index')
                         ->with('success', 'Game berhasil dihapus!');
    }
}