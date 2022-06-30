<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Models\UserRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Microservices\UserService;
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
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function index(Request $request): array
    {
        $this->userService->allows('view', 'users');

        return $this->userService->all($request->input('page', 1));
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

        return new UserResource($this->userService->get($id));
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

        $data = $request->only('first_name', 'last_name', 'email') +
            ['password' => self::DEFAULT_PASSWORD];
        $user = $this->userService->create($data);


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

        $user = $this->userService->update($id, $request->only('first_name', 'last_name', 'email'));

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

        $this->userService->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
