<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AvatarController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        // Delete old avatar
        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
        }

        // Save new avatar directly to public/img/avatars/
        $dir  = public_path('img/avatars');
        File::ensureDirectoryExists($dir);
        $ext  = $request->file('avatar')->getClientOriginalExtension();
        $name = time() . '_' . $user->id . '.' . $ext;
        $request->file('avatar')->move($dir, $name);

        $user->update(['avatar' => 'img/avatars/' . $name]);

        return back()->with('profile_success', 'Profile photo updated.');
    }

    public function remove()
    {
        $user = auth()->user();

        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
        }

        $user->update(['avatar' => null]);

        return back()->with('profile_success', 'Profile photo removed.');
    }
}
