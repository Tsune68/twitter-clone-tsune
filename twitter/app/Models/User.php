<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

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


    public function tweet(): HasMany
    {
        return $this->hasMany('App\Models\Tweet');
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
    public function updateUser(int $userId, Request $request): void
    {
        $userInfo = $this->findByUserId($userId);
        $userInfo->name = $request->name;
        $userInfo->email = $request->email;

        //変更があった箇所だけ保存する
        if ($userInfo->isDirty(['name', 'email'])) {
            $userInfo->save();
        } elseif ($userInfo->isDirty('name')) {
            $userInfo->save(['name']);
        } elseif ($userInfo->isDirty('email')) {
            $userInfo->save(['email']);
        }
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
    public function deleteUser($userId): void
    {
        $user = $this->findByUserId($userId);
        $user->delete();
    }
}
