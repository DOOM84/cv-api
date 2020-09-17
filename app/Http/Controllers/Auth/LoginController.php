<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function action(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'errors' => [
                    'email' => [__('auth.failed')]
                ]
            ], 422);

        }

        if(!$request->user()->status){
            auth()->logout();
            return response()->json([
                'errors' => [
                    'email' => [__('auth.blocked')]
                ]
            ], 403);
        }

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token
            ]
        ]);
    }
}
