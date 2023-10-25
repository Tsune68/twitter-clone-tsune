<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retweet extends Model
{
    use HasFactory;

    protected $fillable = ['tweet_id', 'user_id'];

    /**
     * リツイートしたツイートを取得
     *
     * @return BelongsTo
     */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tweet');
    }
    
    /**
     * リツイートしたユーザーを取得
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * リツイートされているかを調べる
     *
     * @param integer $tweetId
     * @return boolean
     */
    public function isRetweeted(int $tweetId, int $userId): bool
    {
        return $this->where('tweet_id', $tweetId)->where('user_id', $userId)->exists();
    }


    /**
     * リツイートを保存する
     *
     * @param integer $tweetId
     * @param integer $userId
     * @return void
     */
    public function saveRetweet(int $tweetId, int $userId): void
    {
        $this->tweet_id = $tweetId;
        $this->user_id = $userId;
        $this->save();
    }

    /**
     * リツイートを削除する
     *
     * @param integer $tweetId
     * @param integer $userId
     * @return void
     */
    public function deleteRetweet(int $tweetId, int $userId): void
    {
        $this->where('tweet_id', $tweetId)->where('user_id', $userId)->delete();
    }

    /**
     * リツイートする
     *
     * @param integer $tweetId
     * @param integer $userId
     * @return void
     */
    public function retweet(int $tweetId, int $userId): void
    {
        $this->isRetweeted($tweetId, $userId)
            ? $this->deleteRetweet($tweetId, $userId)
            : $this->saveRetweet($tweetId, $userId);
    }

}
