<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($s = $request->input('s')) {
            $query->whereRaw(sprintf("title LIKE '%%%s%%'", $s))
                ->orWhereRaw(sprintf("description LIKE '%%%s%%'", $s));
        }

        return ProductResource::collection($query->get());
    }
}
