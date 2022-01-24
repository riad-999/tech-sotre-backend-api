<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        foreach ($reviews as $review) {
            $sum += $review->score;
        }
        $count = $reviews->count();
        $score = $count ? round($sum / $count, 1) : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category->name,
            'price' => $this->price,
            'featured' => $this->featured,
            'score' => $score,
            'total_reviews' => $reviews->count(),
            'quantity' => $this->quantity,
            'description' => $this->description,
            'images' => json_decode($this->images)
        ];
    }
}