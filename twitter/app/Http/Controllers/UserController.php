<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * 特定のユーザーを取得
     */
    public function findByUserId(int $userId): View
    {
        $user = new User();
        $userInfo = $user->findByUserId($userId);

        if (Gate::denies('view', $userInfo)) {
            abort(403);
        }

        return view('users.show', compact('userInfo'));
    }

    /**
     * ユーザー情報の更新
     */
    public function update(UpdateUserRequest $request, int $userId): RedirectResponse
    {
        $user = new User();
        $user->updateUser($userId, $request);
        return redirect()->route('users.show', ['id' => $userId]);
    }

    /**
     * ユーザー削除する。
     */
    public function delete(int $userId): RedirectResponse
    {
        $user = new User();
        $user->deleteUser($userId);
        return redirect()->route('home');
    }

    /**
     * 全てのユーザーを取得する。
     */
    public function showAllUsers(): View
    {
        $user = new User();
        $allUsers = $user->getAllUsers();
        return view('users.index', compact('allUsers'));
    }

    /**
     * ユーザーフォローする。
     */
    public function follow($userId): RedirectResponse
    {
        $user = new User();
        $user->follow($userId);
        return redirect()->route('users.index');
    }
    
    /**
     * フォローを解除する
     */
    public function unfollow($userId): RedirectResponse
    {
        $user = new User();
        $user->unfollow($userId);
        return redirect()->route('users.index');
    }
}
