<?php

namespace App\Http\Controllers;

use App\Jobs\OrderCompleted;
use App\Models\Link;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Microservices\UserService;
use Symfony\Component\HttpFoundation\Response;

class OrderController
{
    private const INFLUENCER_QUEUE_NAME = 'influencer_queue';
    private const ADMIN_QUEUE_NAME = 'admin_queue';
    private const EMAILS_QUEUE_NAME = 'emails_queue';

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $link = Link::whereCode($request->input('code'))->first();
        $user = (new UserService())->get($link->user_id);

        DB::beginTransaction();
        $order = new Order();
        $order->first_name = $request->input('first_name');
        $order->last_name = $request->input('last_name');
        $order->email = $request->input('email');
        $order->code = $link->code;
        $order->user_id = $user->id;
        $order->influencer_email = $user->email;
        $order->address = $request->input('address');
        $order->address2 = $request->input('address2');
        $order->city = $request->input('city');
        $order->country = $request->input('country');
        $order->zip = $request->input('zip');
        $order->save();

        $lineItems = [];
        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_title = $product->title;
            $orderItem->price = $product->price;
            $orderItem->quantity = $item['quantity'];
            $orderItem->influencer_revenue = (0.1 * ($product->price * $item['quantity']));
            $orderItem->admin_revenue = (0.9 * ($product->price * $item['quantity']));
            $orderItem->save();

            $lineItems[] = [
                'name' => $product->title,
                'description' => $product->description,
                'images' => [
                    $product->image,
                ],
                'amount' => 100 * $product->price,
                'currency' => 'eur',
                'quantity' => $orderItem->quantity,
            ];
        }

        $stripe = Stripe::make(env('STRIPE_SECRET'));
        $source = $stripe->checkout()->sessions()->create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'success_url' => sprintf('%s/success?source={CHECKOUT_SESSION_ID}', env('CHECKOUT_URL')),
            'cancel_url' => sprintf('%s/error', env('CHECKOUT_URL')),
        ]);
        $order->transaction_id = $source['id'];
        $order->save();

        DB::commit();

        return $source;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function confirm(Request $request): Response
    {
        if (!$order = Order::whereTransactionId($request->input('source'))->first()) {
            return response([
                'error' => 'Order not found!'
            ], Response::HTTP_NOT_FOUND);
        }

        $order->complete = 1;
        $order->save();

        $orderItems = [];
        foreach ($order->orderItems as $item) {
            $orderItems[] = $item->toArray();
        }

        OrderCompleted::dispatch($order->toArray(), $orderItems)->onQueue(self::INFLUENCER_QUEUE_NAME);
        OrderCompleted::dispatch($order->toArray(), $orderItems)->onQueue(self::ADMIN_QUEUE_NAME);
        OrderCompleted::dispatch($order->toArray(), $orderItems)->onQueue(self::EMAILS_QUEUE_NAME);

        return response([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}
