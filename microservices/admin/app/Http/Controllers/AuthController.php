<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Microservices\UserService;

class AuthController
{
    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        return new UserResource((new UserService())->getUser());
    }
}
