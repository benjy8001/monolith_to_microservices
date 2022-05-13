<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;

            return compact('token');
        }

        return response([
            'error' => 'Invalid credentials!'
        ], Response::HTTP_UNAUTHORIZED);
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
            ['password' => Hash::make($request->input('password'))]
        );

        return response($user, Response::HTTP_CREATED);
    }
}
