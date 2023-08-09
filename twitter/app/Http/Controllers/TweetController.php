<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTweetRequest;
use App\Http\Requests\CreateReplyRequest;
use App\Models\Reply;
use App\Models\Tweet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

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
     *
     * @param Tweet $tweet
     * @param integer $tweetId
     * @return View
     */
    public function findByTweetId(Tweet $tweet, int $tweetId): View
    {
        $userId = Auth::id();
        $replies = $tweet->getReplies($tweetId);
        $tweet = $tweet->findByTweetId($tweetId);
        $tweet->isFavorite = $tweet->isFavorite($userId);

        return view('tweets.show', compact('tweet', 'replies'));
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

    /**
     * いいねした投稿全てを表示
     * 
     * @param int $userId
     * @return View
     */
    public function showAllFavoriteTweets(Tweet $tweet, int $userId): View
    {
        $AllFavoriteTweets = $tweet->getAllFavoriteTweets($userId);
        foreach ($AllFavoriteTweets as $tweet) {
            $tweet->isFavorite = $tweet->isFavorite($userId);
        }

        return view('tweets.favorite', compact('AllFavoriteTweets'));
    }

    /**
     * リプライを保存する
     *
     * @param Comment $comment
     * @param CreateReplyRequest $request
     * @return RedirectResponse
     */
    public function storeReply(Reply $reply, int $tweetId, CreateReplyRequest $request): RedirectResponse
    {
        $replyMessage = $request->reply;
        $reply->saveReply($tweetId, $replyMessage);

        return redirect()->route('tweets.index');
    }

}
