<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
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
     */
    public function show(int $id): RoleResource
    {
        return new RoleResource(Role::find($id));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $role = Role::find($id);
        $role->update($request->only('name'));

        // @todo : Use of Repository to do that
        DB::table('role_permision')->where('role_id', $role->id)->delete();
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
     * @return Response
     */
    public function destroy(int $id): Response
    {
        DB::table(Role::ROLE_PERMISSION_TABLE_NAME)->where('role_id', $id)->delete();
        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
