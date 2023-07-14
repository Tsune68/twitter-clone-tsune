<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTweetRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Tweet;

class TweetController extends Controller
{

    /**
     * ツイート一覧画面を表示
     */
    public function index(): View
    {
        $tweet = new Tweet();
        $allTweets = $tweet->getAllTweets();
        return view('tweets.index', compact('allTweets'));
    }

    /**
     * ツイート作成画面を表示
     */
    public function create(): View
    {
        return view('tweets.create');
    }

    /**
     * ツイートを保存する
     */
    public function store(CreateTweetRequest $request): RedirectResponse
    {
        $tweet = new Tweet();
        $tweet->saveTweet($request);
        return redirect()->route('tweets.index');
    }

    /**
     * ツイート詳細を表示する
     */
    public function findByTweetId(int $tweetId): View
    {
        $tweetModel = new Tweet();
        $tweet = $tweetModel->findByTweetId($tweetId);

        return view('tweets.show', compact('tweet'));
    }

    /**
     * ツイートを更新する
     */
    public function update(CreateTweetRequest $request, int $tweetId): RedirectResponse
    {
        $tweet = new Tweet();
        $tweet->updateTweet($tweetId, $request);
        return redirect()->route('tweets.index');
    }

    /**
     * ツイート削除する
     */
    public function delete(int $tweetId): RedirectResponse
    {
        $tweet = new Tweet();
        $tweet->deleteTweet($tweetId);
        return redirect()->route('tweets.index');
    }
}
