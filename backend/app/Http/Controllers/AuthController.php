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
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    /**
     * @param Request $request
     * @return UserResource|array|mixed
     */
    public function user(Request $request)
    {
        $headers = [
            'Authorization' => $request->headers->get('Authorization'),
        ];
        $response = Http::withHeaders($headers)->get('http://users_api/api/user');

        return $response->json();

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
}
