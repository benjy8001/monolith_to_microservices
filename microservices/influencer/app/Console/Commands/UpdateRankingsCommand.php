<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Microservices\UserService;

class UpdateRankingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userService = new UserService();
        //request()->header('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNzMxNGYwMzRkM2RjNTZhYzQ5NzhlOWFiMTE1MDM0ZDJjNTkyYjk1YWYzMmIyZTQ3NDYwYTIwNjIzNTEwOTE0ODI0ZDczOGFkMGNjNDY5YzciLCJpYXQiOjE2NTUxOTQ0OTQuMTg3NTY2LCJuYmYiOjE2NTUxOTQ0OTQuMTg3NTcsImV4cCI6MTY4NjczMDQ5NC4xNTY4MjEsInN1YiI6IjIiLCJzY29wZXMiOlsiYWRtaW4iXX0.WzO10Pfz_t2Ovc5XMZ0O2nI9h3DLbbkjbrJSZORRS-kgs1q86_VGPhsWEwtvMxHDHRG5zQFhr_ouUJyOm4piDmqsnZs5rjtuFQIZ9YPNSJw3Kx_jtq3ERRA9Jy6nzMtq2djT9KSTarAhWBgn14Q1I4f7CO9eo7AsRkB3bSe57n9KwQjHVci1ZVHZRcA7PNsW40VKdqzlLPPxP3LRhd-bFpc7N00nUyrhTpAaz2tL0e79HwA83T7nIcNg2MRrNmmIP2uND-Df5zSfqD4KrGD9kcbTQse9cggCmaiL-4C6n84f_fLyJ3gpbMZ-BPBLJqU6_kvePTdfo7AP9ZQH4-7dPBEF8VGWDkdYeEWWh1cLMHd3fTVRroBdENs-4N7pWsh-nXkVPNXd-LayhIJR6-Yn7i7CKGYGnMljd_oKkoYX5eT9KbpnfEecLLLW7D2eZPN6kAVXNDHIC8kfBuCJ6rCoeO2AQ6GUo8SKGBjd_lGuiR2C62Cgjkyfj6hFFsLHBC_lycPX0WJH7qWVsfxdWAYbogjmf0srxNSZlbmyTZheXaLw9aeBC8jHAfFfk71yygX6QBGPzh9bJpYlj5keCH2RFa7neAmyLnsocy8FdfaZkklqC5rPJDezJ04ttsdfHf0nK6vfmZUPbqi03swxaV4AWBLmHYbvUG151qzD189lceM');
        $users = collect($userService->all(-1)); //@todo: Need to byPass Unauthenticated
        $users = $users->filter(function ($user) {
            return $user->is_influencer;
        });

        $users->each(function ($user) {
            $orders = Order::where('user_id', $user->id)->get();
            $revenue = $orders->sum(fn (Order $order) => round($order->total, 2));

            Redis::zadd('rankings', $revenue, $user->fullName());
        });

         return 1;
    }
}
