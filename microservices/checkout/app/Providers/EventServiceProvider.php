<?php

namespace App\Providers;

use App\Jobs\LinkCreated;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        App::bindMethod(ProductCreated::class . '@handle', fn($job) => $job->handle());
        App::bindMethod(ProductUpdated::class . '@handle', fn($job) => $job->handle());
        App::bindMethod(ProductDeleted::class . '@handle', fn($job) => $job->handle());
        App::bindMethod(LinkCreated::class . '@handle', fn($job) => $job->handle());
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
