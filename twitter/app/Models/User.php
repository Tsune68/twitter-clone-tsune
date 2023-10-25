<?php

namespace App\Models;

use App\Models\Follower;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // tweetテーブルにリレーション張る
    public function tweet(): HasMany
    {
        return $this->hasMany('App\Models\Tweet');
    }

    // followersテーブルにリレーション張る（フォローしている人を取得）
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'followers', 
            'follow_user_id', 
            'follower_user_id'
        );
    }

    // followersテーブルにリレーション張る（自分のことをフォローしている人を取得）
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'followers', 
            'follower_user_id', 
            'follow_user_id'
        );
    }

    // favoritesテーブルにリレーション張る
    public function favorites(): BelongsToMany 
    {
        return $this->belongsToMany(
            'App\Models\Tweet', 
            'favorites', 
            'user_id', 
            'tweet_id'
        );
    }

    /**
     * ユーザーが投稿したリプライを取得
     *
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany('App\Models\Reply');
    }

    /**
     * リツイートを取得する
     *
     * @return HasMany
     */
    public function retweets(): HasMany
    {
        return $this->hasMany('Apo\Models\Retweet');
    }

    /**
     * 特定のユーザーを取得
     */
    public function findByUserId(int $userId): User
    {
        $userDetail = $this->find($userId);
        if (is_null($userDetail)) abort(404);

        return $userDetail;
    }

    /**
     * ユーザー情報の更新
     */
    public function updateUser(int $userId, array $request): void
    {
        $userInfo = $this->findByUserId($userId);
        $userInfo -> fill($request)->save();
    }

    /**
     * 全てのユーザーを取得する。
     */
    public function getAllUsers(): Collection
    {
        $allUsers = User::all()->sortByDesc('created_at');

        return $allUsers;
    }

    /**
     * ユーザー削除する。
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->findByUserId($userId);
        $user->delete();
    }

    /**
     * ユーザーフォローする。
     */
    public function follow(int $userId): bool
    {
        $follower = $this->findByUserId($userId);
        $user = Auth::user();
        Follower::create([
            'follow_user_id' => $user->id,
            'follower_user_id' => $follower->id,
        ]);

        return true;
    }

    /**
     * フォローを解除する
     */
    public function unfollow(int $userId): bool
    {
        $follower = $this->findByUserId($userId);
        $user = Auth::user();
        $record = Follower::where('follow_user_id', $user->id)
            ->where('follower_user_id', $follower->id)
            ->first();

        if ($record) {
            $record->delete();

            return true;
        }

        return false;
    }

    /**
     * 特定のユーザーのフォロワーをすべて取得する
     */
    public function getAllFollowers(int $userId): Collection
    {
        $userInfo = $this->findByUserId($userId);
        $allFollowers = $userInfo->followers;

        return $allFollowers;
    }

    /**
     * 特定のユーザーがフォローしているユーザーをすべて取得する
     */
    public function getAllFollows(int $userId): Collection
    {
        $userInfo = $this->findByUserId($userId);
        $allFollows = $userInfo->follows;
        
        return $allFollows;
    }
}
