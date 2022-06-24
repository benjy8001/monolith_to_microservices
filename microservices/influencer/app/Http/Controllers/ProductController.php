<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var Collection $products */
        $products = Cache::remember('products', 60*30, function () use ($request) {
            return Product::all();
        });

        if ($s = $request->input('s')) {
            $s = strtolower($s);
            $products = $products->filter(function (Product $product) use ($s) {
                return (Str::contains(strtolower($product->title), $s) || Str::contains(strtolower($product->description), $s));
            });
        }

        return ProductResource::collection($products);

    }
}
