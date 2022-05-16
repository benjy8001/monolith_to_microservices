<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return ProductResource::collection(Product::paginate());
    }

    /**
     * @param ProductCreateRequest $request
     *
     * @return Response
     */
    public function store(ProductCreateRequest $request): Response
    {
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
        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
