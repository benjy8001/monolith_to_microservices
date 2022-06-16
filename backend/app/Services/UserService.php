<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\Response as AuthResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserService
{
    private $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_API_URL', 'http://users_api/api');
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        $json = $this->request('get', sprintf('%s/%s', $this->endpoint, 'user'))->json();

        return $this->parseUser($json);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->request('get', sprintf('%s/%s', $this->endpoint, 'admin'))->successful();
    }

    /**
     * @return bool
     */
    public function isInfluencer(): bool
    {
        return $this->request('get', sprintf('%s/%s', $this->endpoint, 'influencer'))->successful();
    }

    /**
     * @param string $ability
     * @param string $arguments
     * @return AuthResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allows(string $ability, string $arguments): AuthResponse
    {
        return Gate::forUser($this->getUser())->authorize($ability, $arguments);
    }

    /**
     * @param int $page
     * @return array
     */
    public function all(int $page): array
    {
        return $this->request('get', sprintf('%s/%s?page=%s', $this->endpoint, 'users', $page))->json();
    }

    /**
     * @param int $id
     * @return User
     */
    public function get(int $id): User
    {
        $json = $this->request('get', sprintf('%s/%s/%s', $this->endpoint, 'users', $id))->json();

        return $this->parseUser($json);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $json = $this->request('post', sprintf('%s/%s', $this->endpoint, 'users'), $data)->json();

        return $this->parseUser($json);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     */
    public function update(int $id, array $data): User
    {
        $json = $this->request('put', sprintf('%s/%s/%s', $this->endpoint, 'users', $id), $data)->json();

        return $this->parseUser($json);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->request('delete', sprintf('%s/%s/%s', $this->endpoint, 'users', $id))->successful();
    }

    /**
     * @param array $json
     * @return User
     */
    private function parseUser(array $json): User
    {
        $user = new User();
        $user->id = $json['id'];
        $user->first_name = $json['first_name'];
        $user->last_name = $json['last_name'];
        $user->email = $json['email'];
        $user->is_influencer = $json['is_influencer'] ?? 0;

        return $user;
    }

    /**
     * @param string $page
     * @return Response
     */
    private function request(string $method, string $page, array $data = []): Response
    {
        switch ($method) {
            case 'put' :
            case 'post' :
                return Http::withHeaders($this->headers())->{$method}($page, $data);
            default:
                return Http::withHeaders($this->headers())->{$method}($page);
        }

    }

    /**
     * @return array
     */
    private function headers(): array
    {
        return [
            'Authorization' => request()->headers->get('Authorization') ?: sprintf('Bearer %s', request()->cookie('jwt')),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
