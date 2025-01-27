<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tweet', 'user_id', 'image_path'];

    /**
     * ユーザーデーブルとリレーションをはる
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    // いいね（favorites)テーブルにリレーション張る
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'favorites', 'tweet_id', 'user_id');
    }


    /**
     * Tweetに関連するリプライを取得する
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany('App\Models\Reply');
    }

    /**
     * リツートを取得する
     *
     * @return HasMany
     */
    public function retweets() : HasMany 
    {
        return $this->hasMany('App\Models\Retweet');
    }

    /**
     * ツイートをtweetsテーブルに保存する
     */
    public function saveTweet(string $tweetText, int $userId, ?string $imagePath): void
    {
        $this->tweet = $tweetText;
        $this->user_id = $userId;
        $this->image_path = $imagePath;
        $this->save();
    }

    /**
     * 全てのツイートを取得する。
     */
    public function getAllTweets(): Collection
    {
        return Tweet::all()->sortByDesc('created_at');
    }

    /**
     * 特定のツイートを取得する。
     */
    public function findByTweetId(int $tweetId): Tweet
    {
        $tweet = new Tweet();
        $tweetDetail = $tweet->find($tweetId);
        if (is_null($tweetDetail)) abort(404);

        return $tweet->find($tweetId);
    }

    /**
     * ツイート内容の更新
     */
    public function updateTweet(int $tweetId, Request $request): void
    {
        $tweetInfo = $this->findByTweetId($tweetId);
        $tweetInfo->tweet = $request->tweet;
        $tweetInfo->save();
    }

    /**
     * ツイート削除
     */
    public function deleteTweet(int $tweetId): void
    {
        $tweet = $this->findByTweetId($tweetId);
        $tweet->delete();
    }

    /**
     * ツイート検索する
     */
    public function searchByQuery(string $keyword): Collection
    {
        if (!empty($keyword)) {
            $keywordRemoveSpace = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $keyword);
            $keywordUnifySpace =  mb_convert_kana($keywordRemoveSpace, 's');
            $keywordArray = preg_split('/[\s]+/', $keywordUnifySpace);
            foreach($keywordArray as $keyword) {
                $searchdTweet = Tweet::orWhere('tweet', 'like', "%$keyword%")->get();
            }

            return $searchdTweet;
        }
    }

    /**
     * いいねしているかを調べる
     */
    public function isFavorite(int $userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * いいねする
     */
    public function favoriteTweet(int $userId): void
    {
        $this->isFavorite($userId) 
            ? $this->favorites()->detach($userId)
            : $this->favorites()->attach($userId);
    }

    /**
     * いいねしたツイートを全て取得
     * 
     * @param int $userId
     * @return Collection
     */
    public function getAllFavoriteTweets(int $userId): Collection
    {
        $user = new User();
        $userInfo = $user->findByUserId($userId);
        $AllFavoriteTweets = $userInfo->favorites;
        
        return $AllFavoriteTweets;
    }

    /**
     * リプライを取得する
     *
     * @param integer $tweetId
     * @return Collection
     */
    public function getReplies(int $tweetId): Collection
    {
        $tweet = $this->findByTweetId($tweetId);
        $replies = $tweet->replies;
        
        return $replies;
    }
}
