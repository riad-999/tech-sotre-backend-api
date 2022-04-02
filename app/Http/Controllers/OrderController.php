<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $shippmentFee = 1000;

    public function index()
    {
        $orders = [];
        foreach (Order::oldest()->where('exported', '=', 0)->get() as $order) {
            array_push($orders, [
                'id' => $order->id,
                'buyer' => $order->buyer->name,
                'phone' => $order->buyer->phone
            ]);
        }
        return response(
            ['orders' => $orders]
        );
    }
    public function show(Order $order)
    {
        // return $order;
        $order = new OrderResource($order);
        return response($order);
    }
    public function deliver(Request $request, Order $order)
    {
        $state = $request->all()['state'];
        if ($state == 'delivered') {
            $order->exported = 0;
        } else {
            $order->exported = 1;
        }
        $order->save();
        return response([
            'messsge' => 'action perfomed'
        ]);
    }
    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        $order = $request->session()->get('order', null);
        if (!$order)
            return response(['message' => 'already handled']);

        $items = $order['items'];
        $address = $order['address'];
        $amount = $this->amount($items, 1000);
        $order = new Order([
            'buyer_id' => $user->id,
            'total' => $amount,
            'sub_total' => $amount - $this->shippmentFee,
            'shippment_fee' => $this->shippmentFee,
        ]);
        $order->save();

        $order->address()->create([
            'street' => $address['address'],
            'city' => $address['state'],
            'zip' => $address['zip']
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['id']);
            $product->quantity -= $item['quantity'];
            $product->save();
            $order->products()->attach($product->id, ['quantity' => $item['quantity']]);
        }
        $request->session()->forget('order');
        return response([
            'message' => 'order stored'
        ], 201);
    }
    public function save(Request $request)
    {
        $items = $request->input('items');
        $check = $this->checkQuanity($items);
        if ($check['invalid'])
            return response($check);

        $data = $request->all();
        $request->session()->put('order', $data);
        return response(['message' => 'data sotred'], 201);
    }
    public function destroy(Request $request)
    {
        $request->session()->forget($request->input('name'));
        return response(['message' => 'data destroyed']);
    }
    public function userOrders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::where('buyer_id', $user->id)->latest()->get();
        return response([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ],
            'orders' => $orders
        ]);
    }
    public function productReview(Request $request, Product $product)
    {
        $user = Auth::guard('web')->user();
        $score = $request->input('score');
        $comment = $request->input('comment');
        $review = new Review();
        $review->score = $score;
        $review->comment = $comment ? $comment : null;
        $review->product_id = $product->id;
        $review->user_id = $user->id;
        $review->save();
    }
    private function amount($items, $shippment)
    {
        $amount = $shippment;
        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);
            $amount += $product->price * $item['quantity'];
        }

        return $amount;
    }
    private function checkQuanity($items)
    {
        $check = ['invalid' => false, 'messages' => []];
        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);
            if ($item['quantity'] > $product->quantity) {
                array_push($check['messages'], "only $product->quantity $product->name are availabe");
                $check['invalid'] = true;
            }
        }
        return $check;
    }
}