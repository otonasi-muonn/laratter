<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $tweets = $user
            ->bookmarks()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('bookmarks.index', compact('tweets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Tweet $tweet)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->bookmarks()->syncWithoutDetaching([$tweet->id]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->bookmarks()->detach($tweet->id);
        return back();
    }
}
