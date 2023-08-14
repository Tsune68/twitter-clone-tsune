<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['reply', 'user_id', 'tweet_id'];

    /**
     * リプライが関連するツイートを取得
     *
     * @return BelongsTo
     */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tweet');
    }
    
    /**
     * リプライが関連するユーザーを取得
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * リプライを保存する
     *
     * @param integer $tweetID
     * @param string $replyMessage
     * @return void
     */
    public function saveReply(int $tweetID, string $replyMessage): void
    {
        $this->reply = $replyMessage;
        $this->user_id = auth()->id();
        $this->tweet_id = $tweetID;
        $this->save();
    }

    /**
     * 特定のリプライを取得する
     *
     * @param integer $replyId
     * @return Reply
     */
    public function findReply(int $replyId): Reply
    {
        $replyDetail = $this->find($replyId);

        return $replyDetail;
    }

    /**
     * リプライを削除する
     *
     * @param integer $replyId
     * @return void
     */
    public function deleteReply(int $replyId): void
    {
        $this->destroy($replyId);
    }

    /**
     * リプライを更新する
     *
     * @param integer $replyId
     * @param string $replyMessage
     * @return void
     */
    public function updateReply(int $replyId, string $replyMessage): void
    {
        $replyDetail = $this->findReply($replyId);
        $replyDetail->reply = $replyMessage;
        $replyDetail->save();
    }
}
