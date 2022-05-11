<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /** @var string */
    private const DEFAULT_PASSWORD = 'password';

    public function index(): string
    {
        return User::all();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function show(int $id)
    {
        return User::find($id);
    }

    /**
     * @param UserCreateRequest $request
     * @return Response
     */
    public function store(UserCreateRequest $request): Response
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email') +
            ['password' => Hash::make(self::DEFAULT_PASSWORD)]
        );

        return response($user, Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UserUpdateRequest $request, int $id): Response
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function destroy(int $id): Response
    {
        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
