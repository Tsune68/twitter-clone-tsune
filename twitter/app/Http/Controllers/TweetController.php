<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTweetRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TweetController extends Controller
{

    /**
     * ツイート一覧画面を表示
     */
    public function index(Request $request): View
    {
        $tweet = new Tweet();
        $keyword = $request->input('keyword');
        $allTweets = !empty($keyword) ? $tweet->searchByQuery(($keyword)) : $tweet->getAllTweets();

        $userId = Auth::id();
        foreach ($allTweets as $tweet) {
            $tweet->isFavorite = $tweet->isFavorite($userId);
        }
        return view('tweets.index', compact('allTweets', 'keyword'));
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
        $userId = Auth::id();
        $tweet = $tweetModel->findByTweetId($tweetId);
        $tweet->isFavorite = $tweet->isFavorite($userId);

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

    /**
     * ツイートにいいねする
     */
    public function favorite(Request $request): JsonResponse
    {
        $tweetId = $request->tweetId;
        $tweet = Tweet::find($tweetId);
        $userId = Auth::id();

        $tweet->favoriteTweet($userId);
        $tweetFavoritesCount = $tweet->favorites()->count();
        $json = [
            'tweetFavoritesCount' => $tweetFavoritesCount,
        ];
        return response()->json($json);
    }
}
