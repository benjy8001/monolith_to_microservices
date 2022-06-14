<?php

namespace App\Console\Commands;

use App\Jobs\AdminAdded;
use Illuminate\Console\Command;

class FireEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fire event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        AdminAdded::dispatch('a@a.com');
    }
}
