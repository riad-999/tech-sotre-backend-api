<?php

namespace App\Http\Resources;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->buyer_id);
        $products = [];
        foreach ($this->products as $product) {
            $review = Review::where('user_id', $user->id)->where('product_id', $product->id)->first();
            array_push($products, [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'images' => json_decode($product->images, true),
                'quantity' => $product->pivot->quantity,
                'review' => $review ? $review->score : null
            ]);
        }

        return [
            'products' => $products,
            'exported' => $this->exported,
            'user' => [
                'name' => $user->name,
                'phone' => $user->phone,
                'address' => $user->address->street,
                'state' => $user->address->city,
                'zip' => $user->address->zip
            ]
        ];
    }
}