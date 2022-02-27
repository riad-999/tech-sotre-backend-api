<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;
use Throwable;

class StripeController extends Controller
{
    private $shippmentFee = 1000;

    public function createPaymentIntent(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $items = $request->all()['IDs'];
            $shippment = 1000;
            $amount = 0;
            foreach ($items as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    throw new \ErrorException('invalid product id');
                }
                $amount += $product->price * $item['quantity'];
            }
            $amount += $shippment;
            $paymentIntent = Stripe\PaymentIntent::create([

                'amount' => $amount,

                'currency' => 'usd',

                'payment_method_types' => ['card']
            ]);
            $clientSecret = $paymentIntent->client_secret;
        } catch (Throwable $error) {
            return response([
                'error' => true,
                'message' => $error->getMessage()
            ]);
        }
        return response([
            'clientSecret' => $clientSecret,
            'total' => $amount,
            'subtotal' => $amount - $shippment,
            'shipement' => $shippment,
            'message' => 'payment intent generated successfully'
        ]);
    }
    public function handleOrder(Request $request)
    {
        $user = Auth::guard('web')->user();
        $items = $request->all()['items'];
        $address = $request->all()['address'];
        $amount = $this->amount($items, 1000);
        // return response([
        //     'user' => $user
        // ]);
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
            $order->products()->attach($product->id, ['quantity' => $item['quantity']]);
        }

        return response([
            'id' => $order->id,
            'message' => 'all good'
        ], 201);
    }
    public function cancelOrder(Request $request)
    {
        $id = $request->all()['id'];

        $order = Order::find($id);

        $order->delete();

        return response([
            'order' => $order,
            'message' => 'order canceled successfully'
        ]);
    }
    public function checkAddress(Request $request)
    {
        $fields = $request->validate([
            'address' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric|digits:5'
        ]);

        return response([
            'message' => 'all good'
        ]);
    }
    private function amount($items, $shippment)
    {
        $amount = 0;
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                throw new \ErrorException('invalid product id');
            }
            $amount += $product->price * $item['quantity'];
        }
        $amount += $shippment;

        return $amount;
    }
}