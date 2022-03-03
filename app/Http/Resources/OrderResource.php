<?php

namespace App\Http\Resources;

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
            array_push($products, [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity
            ]);
        }

        return [
            'products' => $products,
            'user' => [
                'name' => $user->name,
                'address' => $user->address->street,
                'state' => $user->address->city,
                'zip' => $user->address->zip
            ]
        ];
    }
}