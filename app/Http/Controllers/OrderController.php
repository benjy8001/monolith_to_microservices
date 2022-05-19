<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('view', 'orders');

        return OrderResource::collection(Order::paginate());
    }

    /**
     * @param int $id
     *
     * @return OrderResource
     */
    public function show(int $id): OrderResource
    {
        Gate::authorize('view', 'orders');

        return new OrderResource(Order::find($id));
    }

    /**
     * @return StreamedResponse
     */
    public function export(): StreamedResponse
    {
        Gate::authorize('view', 'orders');

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $callback = function () {
            $orders = Order::all();
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Id', 'Name', 'Email',  'Product Title', 'Price', 'Quantity']);
            foreach ($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);
                foreach ($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }
            fclose($file);
        };

        return ResponseFacade::stream($callback, Response::HTTP_OK, $headers);
    }
}
