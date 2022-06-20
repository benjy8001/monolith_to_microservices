<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;

class LinkController
{
    /**
     * @param string $code
     * @return LinkResource
     */
    public function show(string $code): LinkResource
    {
        return new LinkResource(Link::where('code', $code)->first());
    }
}
