<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviews = $this->reviews;
        $sum = 0;
        $comments = [];
        foreach ($reviews as $review) {
            $sum += $review->score;
            if ($review->comment)
                array_push($comments, [
                    'user' => $review->user->name,
                    'score' => $review->score,
                    'comment' => $review->comment
                ]);
        }
        // $sold = $this->orders->count();
        $orders = $this->orders;
        $sold = 0;
        foreach ($orders as $order) {
            $sold += $order->pivot->quantity;
        }
        $score = round($sum / $reviews->count(), 1);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'price' => $this->price,
            'score' => $score,
            'sold' => $sold,
            'comments' => $comments,
            'total_reviews' => $reviews->count(),
            'quantity' => $this->quantity,
            'description' => $this->description,
            'images' => json_decode($this->images)
        ];
    }
}