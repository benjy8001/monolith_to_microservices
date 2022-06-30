<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Microservices\UserService;
use Symfony\Component\HttpFoundation\Response;

class ProductController
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
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->userService->allows('view', 'products');

        return ProductResource::collection(Product::paginate());
    }

    /**
     * @param ProductCreateRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(ProductCreateRequest $request): Response
    {
        $this->userService->allows('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        ProductCreated::dispatch($product->toArray())->onQueue(self::RABBITMQ_CHECKOUT_QUEUE);

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     *
     * @return ProductResource
     * @throws AuthorizationException
     */
    public function show(int $id): ProductResource
    {
        $this->userService->allows('view', 'products');

        return new ProductResource(Product::find($id));
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
        $this->userService->allows('edit', 'products');

        $product = Product::find($id);
        $product->update($request->only('title', 'description', 'image', 'price'));

        ProductUpdated::dispatch($product->toArray())->onQueue(self::RABBITMQ_CHECKOUT_QUEUE);

        return response(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $this->userService->allows('edit', 'products');

        Product::destroy($id);
        ProductDeleted::dispatch($id)->onQueue(self::RABBITMQ_CHECKOUT_QUEUE);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
