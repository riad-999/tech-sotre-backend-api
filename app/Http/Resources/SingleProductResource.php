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
        $count = 0;
        $comments = [];
        foreach ($reviews as $review) {
            if (!$review->score)
                continue;
            $sum += $review->score;
            $count++;
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
        $score = $count ? round($sum / $count, 1) : 0;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category ? $this->category->name : null,
            'price' => $this->price,
            'score' => $score,
            'sold' => $sold,
            'comments' => $comments,
            'total_reviews' => $reviews->count(),
            'quantity' => $this->quantity,
            'description' => $this->description,
            'featured' => $this->featured,
            'images' => json_decode($this->images)
        ];
    }
}