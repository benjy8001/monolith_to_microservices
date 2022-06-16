<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Models\User;
use App\Models\UserRole;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /** @var string */
    private const DEFAULT_PASSWORD = 'password';
    private $userService;

    /**
     * AuthController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->userService->allows('view', 'users');

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
        $this->userService->allows('view', 'users');

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
        $this->userService->allows('edit', 'users');

        $user = User::create(
            $request->only('first_name', 'last_name', 'email') +
            ['password' => Hash::make(self::DEFAULT_PASSWORD)]
        );

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        AdminAdded::dispatch($user->email);

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
        $this->userService->allows('edit', 'users');

        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        UserRole::where('user_id', $user->id)->delete();
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

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
        $this->userService->allows('edit', 'users');

        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
