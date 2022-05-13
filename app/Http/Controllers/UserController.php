<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /** @var string */
    private const DEFAULT_PASSWORD = 'password';

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $user = User::paginate();

        return UserResource::collection($user);
    }

    /**
     * @param int $id
     *
     * @return UserResource
     */
    public function show(int $id): UserResource
    {
        $user = User::find($id);

        return new UserResource($user);
    }

    /**
     * @param UserCreateRequest $request
     * @return Response
     */
    public function store(UserCreateRequest $request): Response
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            ['password' => Hash::make(self::DEFAULT_PASSWORD)]
        );

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UserUpdateRequest $request, int $id): Response
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return UserResource
     */
    public function user(): UserResource
    {
        return new UserResource(Auth::user());
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
