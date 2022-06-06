<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /** @var string */
    private const DEFAULT_PASSWORD = 'password';

    /**
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('view', 'users');

        return UserResource::collection(User::paginate());
    }

    /**
     * @param int $id
     *
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(int $id): UserResource
    {
        Gate::authorize('view', 'users');

        return new UserResource(User::find($id));
    }

    /**
     * @param UserCreateRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request): Response
    {
        Gate::authorize('edit', 'users');

        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id') +
            ['password' => Hash::make(self::DEFAULT_PASSWORD)]
        );

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * @param UserUpdateRequest $request
     * @param int               $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update(UserUpdateRequest $request, int $id): Response
    {
        Gate::authorize('edit', 'users');

        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        Gate::authorize('edit', 'users');

        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
