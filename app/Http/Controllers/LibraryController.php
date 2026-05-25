<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user  = Auth::user();
        $query = $user->libraryGames()->with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $games = $query->paginate(12)->withQueryString();

        return view('library.index', compact('games'));
    }
}