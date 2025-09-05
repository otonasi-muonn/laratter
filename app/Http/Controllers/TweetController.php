<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 全てのツイートを取得してビューに渡す
        // - user リレーションをロードして N+1 問題を回避
        // - latest() で作成日時の降順に並べ替え
        $tweets = Tweet::with('user')->latest()->get();
        // tweets.index ビューへ tweets 変数として渡す
        return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ツイート作成フォームを表示する
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 入力バリデーション
        // - tweet は必須、最大 255 文字
        $request->validate([
            'tweet' => 'required|max:255',
        ]);

        // 認証済みユーザーのリレーション経由でツイートを作成
        // - $request->user() は現在ログイン中のユーザー
        // - tweets() リレーションに対して create() を呼ぶことで
        //   user_id 等の外部キーが自動的にセットされる
        $request->user()->tweets()->create($request->only('tweet'));

        // 作成後はツイート一覧へリダイレクト
        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        // 指定されたツイートを表示する（未実装）
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        // 指定されたツイートの編集フォームを表示する（未実装）
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        // ツイート更新処理（未実装）
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // ツイート削除処理（未実装）
    }
}
