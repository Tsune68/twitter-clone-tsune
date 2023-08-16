<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTweetRequest;
use App\Http\Requests\CreateReplyRequest;
use App\Models\Reply;
use App\Models\Tweet;
use App\Services\ImagePath;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    public function store(CreateTweetRequest $request, Tweet $tweet, ImagePath $imagePath): RedirectResponse
    {
        $tweetText = $request->tweet;
        $userId = $request->user()->id;
        $imageFilePath = null;
        try {
            if ($request->file('image')) {
                $imageFilePath = $imagePath->getImagePath(
                    $request->file('image'), config('directoryName.tweet')
                );
            }
            $tweet->saveTweet($tweetText, $userId, $imageFilePath);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('tweets.index')
                ->with('flash_message', '予期せぬエラーが発生しました。もう一度やり直してください。');
        }

        return redirect()->route('tweets.index')
            ->with('flash_message', 'ツイートが完了しました！');
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
     * @param CreateReplyRequest $request
     * @param Reply $reply
     * @param integer $tweetId
     * @return RedirectResponse
     */
    public function storeReply(
        CreateReplyRequest $request,
        Reply $reply,
        int $tweetId,
    ): RedirectResponse {
        $replyMessage = $request->reply;
        $reply->saveReply($tweetId, $replyMessage);

        return redirect()->route('tweets.show', ['id' => $tweetId]);
    }

    /**
     * リプライを削除する
     *
     * @param Reply $reply
     * @param integer $replyId
     * @return RedirectResponse
     */
    public function deleteReply(Reply $reply, int $replyId): RedirectResponse
    {
        try {
            $replyDetail = $reply->findReply($replyId);
            $tweetId = $replyDetail->tweet->id;
            $reply->deleteReply($replyId);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('tweets.index')
                ->with('flash_message', '予期せぬエラーが発生しました。もう一度やり直してください。');
        }

        return redirect()->route('tweets.show', ['id' => $tweetId])
            ->with('flash_message', 'リプライの削除が完了しました！');
    }

    /**
     * リプライを編集する
     *
     * @param CreateReplyRequest $request
     * @param Reply $reply
     * @param integer $replyId
     * @return RedirectResponse
     */
    public function updateReply(CreateReplyRequest $request, Reply $reply, int $replyId): RedirectResponse
    {
        try {
            $replyDetail = $reply->findReply($replyId);
            $tweetId = $replyDetail->tweet->id;
            $replyMessage = $request->reply;    
            $reply->updateReply($replyId, $replyMessage);
        } catch(Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('tweets.index')
                ->with('flash_message', '予期せぬエラーが発生しました。もう一度やり直してください。');
        }

        return redirect()->route('tweets.show', ['id' => $tweetId])
            ->with('flash_message', 'リプライの編集に成功しました！');
    }
}
