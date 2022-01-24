<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'score' => rand(1, 5),
            'comment' => rand(0, 1) ? implode('', $this->faker->paragraphs(rand(1, 5))) : null
        ];
    }
}