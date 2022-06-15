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
}
