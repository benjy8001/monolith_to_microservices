<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request): Response
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;

            $cookie = Cookie::make('jwt', $token, 3600);

            return response(compact('token'))->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid credentials!'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return Response
     */
    public function register(RegisterRequest $request): Response
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email') +
            [
                'password' => Hash::make($request->input('password')),
                'role_id' => 1,
                'is_influencer' => 1,
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        $user = Auth::user();

        $resource = new UserResource($user);
        if ($user->isInfluencer()) {
            return $resource;
        }

        return $resource->additional([
            'data' => [
                'permissions' => $user->permissions(),
            ]
        ]);
    }

    /**
     * @param UpdateInfoRequest $request
     * @return Response
     */
    public function updateInfo(UpdateInfoRequest $request): Response
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return Response
     */
    public function updatePassword(UpdatePasswordRequest $request): Response
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
