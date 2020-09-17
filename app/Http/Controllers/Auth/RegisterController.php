<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;

class RegisterController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;

    }

    public function action(RegisterRequest $request)
    {
        $user = $this->users->create($request->all());
        return new UserResource($user->fresh());
    }
}
