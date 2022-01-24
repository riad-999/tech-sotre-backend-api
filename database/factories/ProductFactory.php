<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(16),
            'price' => $this->faker->numberBetween(100, 500000),
            'quantity' => $this->faker->numberBetween(0, 250),
            'description' => implode('', $this->faker->paragraphs(rand(1, 5))),
            'images' => json_encode([
                'main' => 'home.jpg',
                'others' => [
                    '/images/2343423.jpg',
                    '/images/2343423.jpg',
                    '/images/2343423.jpg'
                ]
            ])
        ];
    }
}