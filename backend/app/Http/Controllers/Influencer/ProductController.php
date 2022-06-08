<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return Cache::remember('products', 30, function () use ($request) {
            sleep(5);
            $query = Product::query();
            if ($s = $request->input('s')) {
                $query->whereRaw(sprintf("title LIKE '%%%s%%'", $s))
                    ->orWhereRaw(sprintf("description LIKE '%%%s%%'", $s));
            }

            return ProductResource::collection($query->get());
        });

    }
}
