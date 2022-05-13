<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return Role::all();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $role = Role::create($request->only('name'));

        return response($role, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return Role
     */
    public function show(int $id): Role
    {
        return Role::find($id);
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

        return response($role, Response::HTTP_ACCEPTED);

    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
