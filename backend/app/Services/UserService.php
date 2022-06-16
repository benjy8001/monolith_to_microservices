<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
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
        $json = Http::withHeaders($this->headers())->get(sprintf('%s/%s', $this->endpoint, 'user'))->json();

        $user = new User();
        $user->id = $json['id'];
        $user->first_name = $json['first_name'];
        $user->last_name = $json['last_name'];
        $user->email = $json['email'];
        $user->is_influencer = $json['is_influencer'];
        $user->email = $json['email'];

        return $user;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return Http::withHeaders($this->headers())->get(sprintf('%s/%s', $this->endpoint, 'admin'))->successful();
    }

    /**
     * @return bool
     */
    public function isInfluencer(): bool
    {
        return Http::withHeaders($this->headers())->get(sprintf('%s/%s', $this->endpoint, 'influencer'))->successful();
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
