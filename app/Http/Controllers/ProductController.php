<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('view', 'products');

        return ProductResource::collection(Product::paginate());
    }

    /**
     * @param ProductCreateRequest $request
     *
     * @return Response
     */
    public function store(ProductCreateRequest $request): Response
    {
        Gate::authorize('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     *
     * @return ProductResource
     */
    public function show(int $id): ProductResource
    {
        Gate::authorize('view', 'products');

        return new ProductResource(Product::find($id));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        Gate::authorize('edit', 'products');

        $product = Product::find($id);
        $product->update($request->only('title', 'description', 'image', 'price'));

        return response(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        Gate::authorize('edit', 'products');

        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
