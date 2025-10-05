<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        // if ($request->hasFile('avatar')) {
        //     if (!empty($request->user()->avatar)) {
        //         Storage::disk('public')->delete($request->user()->avatar);
        //     }
        //     $img_path = $request->file('avatar')->store('img', 'public');
        //     $validated['avatar'] = $img_path;
        // }

        if ($request->avatar) {
            if (!empty($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $newFileName = Str::after($request->avatar, 'tmp/');

            Storage::disk('public')->move($request->avatar, "img/$newFileName");

            $validated['avatar'] = "img/$newFileName";
        }

        $user->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:10240'],
        ]);

        $imgPath = $request->file('avatar')->store('tmp', 'public');

        return response()->json([
            'path' => $imgPath,
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
