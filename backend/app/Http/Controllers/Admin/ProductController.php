<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductUpdatedEvent;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use App\Models\Product;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProductController
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

        event(new ProductUpdatedEvent());
        ProductCreated::dispatch($product->toArray());

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

        event(new ProductUpdatedEvent());
        ProductUpdated::dispatch($product->toArray());

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
        ProductDeleted::dispatch($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
