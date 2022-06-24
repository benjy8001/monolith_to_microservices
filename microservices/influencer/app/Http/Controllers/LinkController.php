<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
//use App\Jobs\LinkCreated;
use App\Models\Link;
use App\Models\LinkProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Microservices\UserService;

class LinkController
{
    private const RABBITMQ_CHECKOUT_QUEUE = 'checkout_queue';
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
     * @return LinkResource
     */
    public function store(Request $request): LinkResource
    {
        $user = $this->userService->getUser();

        $link = Link::create([
            'user_id' => $user->id,
            'code' => Str::random(6),
        ]);

        $linkProducts = [];
        foreach ($request->input('products') as $productId) {
            $linkProduct = LinkProduct::create([
               'link_id' => $link->id,
               'product_id' => $productId,
            ]);
            $linkProducts[] = $linkProduct->toArray();
        }

        //LinkCreated::dispatch($link->toArray(), $linkProducts)->onQueue(self::RABBITMQ_CHECKOUT_QUEUE);

        return new LinkResource($link);
    }
}
