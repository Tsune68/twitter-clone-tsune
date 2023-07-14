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
    public function findByUserId(int $userId): View
    {
        $user = new User();
        $userInfo = $user->findByUserId($userId);

        if (Gate::denies('view', $userInfo)) {
            abort(403);
        }

        return view('users.show', compact('userInfo'));
    }

    public function update(UpdateUserRequest $request, int $userId): RedirectResponse
    {
        $user = new User();
        $user->updateUser($userId, $request);
        return redirect()->route('users.show', ['id' => $userId]);
    }

    public function delete(int $userId): RedirectResponse
    {
        $user = new User();
        $user->deleteUser($userId);
        return redirect()->route('home');
    }

    public function showAllUsers(): View
    {
        $user = new User();
        $allUsers = $user->getAllUsers();
        return view('users.index', compact('allUsers'));
    }
}
