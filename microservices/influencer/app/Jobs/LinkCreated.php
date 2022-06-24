<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $link;
    private $linkProducts;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $link, array $linkProducts)
    {
        $this->link = $link;
        $this->linkProducts = $linkProducts;
    }
}
