<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumReply;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth' => ['except' => ['index', 'show']],
        ];
    }

    public function index(Request $request)
    {
        $query = Forum::with('user', 'game')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $forums = $query->paginate(15)->withQueryString();

        return view('community.forum.index', compact('forums'));
    }

    public function create()
    {
        $games = Game::active()->orderBy('title')->get();
        return view('community.forum.create', compact('games'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'body'     => ['required', 'string', 'min:10'],
            'category' => ['required', 'in:general,tips,review,bug_report'],
            'game_id'  => ['nullable', 'exists:games,id'],
        ]);

        Forum::create([
            'user_id'  => $user->id,
            'title'    => $validated['title'],
            'body'     => $validated['body'],
            'category' => $validated['category'],
            'game_id'  => $validated['game_id'] ?? null,
        ]);

        return redirect()->route('community.forum.index')
            ->with('success', 'Diskusi berhasil dibuat!');
    }

    public function show(Forum $forum)
    {
        $forum->increment('views');
        $forum->load('user', 'game', 'replies.user');

        return view('community.forum.show', compact('forum'));
    }

    public function destroy(Forum $forum)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($forum->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $forum->delete();

        return redirect()->route('community.forum.index')
            ->with('success', 'Diskusi berhasil dihapus.');
    }

    public function reply(Request $request, Forum $forum)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        ForumReply::create([
            'forum_id' => $forum->id,
            'user_id'  => $user->id,
            'body'     => $validated['body'],
        ]);

        return back()->with('success', 'Balasan berhasil ditambahkan!');
    }

    public function destroyReply(ForumReply $reply)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($reply->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $reply->delete();

        return back()->with('success', 'Balasan dihapus.');
    }
}