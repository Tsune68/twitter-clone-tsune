<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['reply', 'user_id', 'tweet_id'];

    
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

}
