<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public static function middleware(): array
    {
        return ['auth'];
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('orders', 'libraryGames', 'wishlists');

        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $user->id],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'avatar'   => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar !== 'default-avatar.png') {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $filename;
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}