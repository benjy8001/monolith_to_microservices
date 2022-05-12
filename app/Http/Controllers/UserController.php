<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /** @var string */
    private const DEFAULT_PASSWORD = 'password';

    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return User::paginate();
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
     * @return Response
     */
    public function destroy(int $id): Response
    {
        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return Auth::user();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function updateInfo(Request $request): Response
    {
        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePassword(Request $request): Response
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, Response::HTTP_ACCEPTED);
    }
}
