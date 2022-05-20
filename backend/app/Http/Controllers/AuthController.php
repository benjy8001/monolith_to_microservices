<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
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
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            ['password' => Hash::make($request->input('password'))]
        );

        return response($user, Response::HTTP_CREATED);
    }
}
