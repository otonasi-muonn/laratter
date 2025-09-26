<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Tweet;

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    public function show(User $user)
    {
        // Intelephense 対応のため Auth ファサードでチェック
        $isOwnProfile = Auth::check() && Auth::id() === $user->id;

        if ($isOwnProfile) {
            $tweets = Tweet::query()
                ->where('user_id', $user->id)  // 自分のツイート
                ->orWhereIn('user_id', $user->follows->pluck('id')) // フォローしているユーザーのツイート
                ->latest()
                ->paginate(10);
        } else {
            // 他のユーザーの場合、そのユーザーのツイートのみを取得
            $tweets = $user
                ->tweets()
                ->latest()
                ->paginate(10);
        }

        $user->load(['follows', 'followers']);

        return view('profile.show', compact('user', 'tweets'));
    }

    /**
     * Show the list of users that the given user is following.
     */
    public function following(User $user)
    {
        $users = $user->follows()->paginate(20);
        return view('profile.following', compact('user', 'users'));
    }

    /**
     * Show the list of followers for the given user.
     */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(20);
        return view('profile.followers', compact('user', 'users'));
    }
}
