<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        /** @var User $user */
        $user    = Auth::user();
        $friends = $user->friends();
        $pending = Friend::where('receiver_id', $user->id)
                         ->where('status', 'pending')
                         ->with('sender')
                         ->get();

        return view('community.friends.index', compact('friends', 'pending'));
    }

    public function send(User $user)
    {
        /** @var User $sender */
        $sender = Auth::user();

        if ($sender->id === $user->id) {
            return back()->with('error', 'Tidak bisa menambahkan diri sendiri!');
        }

        if ($sender->isFriendWith($user->id) || $sender->hasPendingRequestWith($user->id)) {
            return back()->with('info', 'Permintaan sudah dikirim atau sudah berteman.');
        }

        Friend::create([
            'sender_id'   => $sender->id,
            'receiver_id' => $user->id,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Permintaan pertemanan dikirim ke ' . $user->name . '!');
    }

    public function accept(Friend $friend)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($friend->receiver_id !== $user->id) {
            abort(403);
        }

        $friend->update(['status' => 'accepted']);

        return back()->with('success', 'Permintaan pertemanan diterima!');
    }

    public function reject(Friend $friend)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($friend->receiver_id !== $user->id) {
            abort(403);
        }

        $friend->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan pertemanan ditolak.');
    }

    public function unfriend(User $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        Friend::where(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
        })->delete();

        return back()->with('success', $user->name . ' dihapus dari daftar teman.');
    }

public function search(Request $request)
{
    /** @var User $authUser */
    $authUser = Auth::user();
    $query    = $request->get('q');
    $users    = collect();

    if ($query) {
        $users = User::where('id', '!=', $authUser->id)
                     ->where('role', 'user')
                     ->where(function ($q) use ($query) {
                         $q->where('name', 'like', '%' . $query . '%')
                           ->orWhere('username', 'like', '%' . $query . '%');
                     })
                     ->take(10)
                     ->get();
    }

    // ✅ Tambahkan $pending dan $friends
    $pending = Friend::where('receiver_id', $authUser->id)
                     ->where('status', 'pending')
                     ->with('sender')
                     ->get();

    $friends = $authUser->friends();

    return view('community.friends.search', compact('users', 'query', 'pending', 'friends'));
}
}