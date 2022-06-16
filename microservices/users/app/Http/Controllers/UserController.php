<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        return PaginatedResource::collection(User::paginate());
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function show(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->only('first_name', 'last_name', 'email')
            + ['password' => Hash::make($request->input('password'))];
        $user = User::create($data);

        return response($user, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
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
}
