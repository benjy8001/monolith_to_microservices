<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Microservices\UserService;
use Symfony\Component\HttpFoundation\Response;

class RoleController
{
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
        $this->userService->allows('view', 'roles');

        return RoleResource::collection(Role::all());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(Request $request): Response
    {
        $this->userService->allows('edit', 'roles');

        $role = Role::create($request->only('name'));
        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table(Role::ROLE_PERMISSION_TABLE_NAME)->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     *
     * @return RoleResource
     * @throws AuthorizationException
     */
    public function show(int $id): RoleResource
    {
        $this->userService->allows('view', 'roles');

        return new RoleResource(Role::find($id));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id): Response
    {
        $this->userService->allows('edit', 'roles');

        $role = Role::find($id);
        $role->update($request->only('name'));

        // @todo : Use of Repository to do that
        DB::table('role_permission')->where('role_id', $role->id)->delete();
        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table(Role::ROLE_PERMISSION_TABLE_NAME)->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);

    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $this->userService->allows('edit', 'roles');

        DB::table(Role::ROLE_PERMISSION_TABLE_NAME)->where('role_id', $id)->delete();
        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
